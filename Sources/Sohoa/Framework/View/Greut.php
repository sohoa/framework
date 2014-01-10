<?php

/**
 * Se mettre d'accord sur la doc.
 */

namespace Sohoa\Framework\View {

    use Hoa\Http\Response\Response;
    use Hoa\Stream\IStream\Out;
    use Hoa\View\Viewable;
    use Sohoa\Framework\Exception;
    use Sohoa\Framework\Framework;
    use Sohoa\Framework\Kit;
    use Sohoa\Framework\View\Helper as Helper;

    class Greut implements Viewable
    {


        private $_out = null;
        private $_data = null;
        private $_paths = null;
        private $_inherits = array();
        private $_blocks = array();
        private $_blocknames = array();
        private $_file = '';
        private $_headers = array();
        protected $_helpers = array();

        public function __construct(Out $response = null)
        {
            Kit::add('greut', new Kit\Greut());

            if ($response === null)
                $response = new Response();

            $this->_out   = $response;
            $this->_data  = new \Stdclass();
            $this->_paths = 'hoa://Application/View/';
        }

        public function setOutputStream(Out $response)
        {
            $this->_out = $response;
        }

        public function getOutputStream()
        {
            return $this->_out;
        }

        public function getData()
        {
            return $this->_data;
        }

        public function getRouter()
        {
            return Framework::services('router');
        }

        public function setPath($path)
        {
            if ($path[strlen($path) - 1] !== '/')
                $path .= '/';

            $this->_paths = $path;
        }

        public function __get($helperName)
        {
            if (!isset($this->_helpers[$helperName])) {
                $helperClassName = '\\Sohoa\Framework\\View\\Helper\\' . ucfirst($helperName);
                if (!class_exists($helperClassName, true)) {
                    $helperClassName = '\\Application\\View\\Helper\\' . ucfirst($helperName);
                }
                $this->_helpers[$helperName] = new $helperClassName();
                if ($this->_helpers[$helperName] instanceof Helper) {
                    $this->_helpers[$helperName]->setView($this);
                }
            }

            return $this->_helpers[$helperName];
        }

        public function inherits($path)
        {
            $this->_inherits[$this->_file][] = $path;
        }

        public function block($blockname, $mode = "replace")
        {
            $this->_blocknames[] = array($blockname, $mode);
            ob_start("mb_output_handler");
        }

        public function endblock()
        {
            list($blockname, $mode) = array_pop($this->_blocknames);

            if (!isset($this->_blocks[$blockname]) && $mode !== false) {
                $this->_blocks[$blockname] = array("content" => ob_get_contents(), "mode" => $mode);
            } else {
                switch ($this->_blocks[$blockname]["mode"]) {
                    case "before":
                    case "prepend":
                        $this->_blocks[$blockname] = array(
                            "content" => $this->_blocks[$blockname]["content"] . ob_get_contents(),
                            "mode"    => $mode
                        );
                        break;
                    case "after":
                    case "append":
                        $this->_blocks[$blockname] = array(
                            "content" => ob_get_contents() . $this->_blocks[$blockname]["content"],
                            "mode"    => $mode
                        );
                        break;
                }
            }

            ob_end_clean();

            if ($mode === "replace") {
                echo $this->_blocks[$blockname]["content"];
            }
        }

        public function getFilenamePath($filename)
        {
            $realpath = $filename;
            if (preg_match('#^[a-zA-Z0-9\.^\\\\]+#', $filename) === 1) {
                if (substr($filename, 0, 6) !== 'hoa://') {
                    $filename = $this->_paths . $filename;
                    $realpath = realpath($filename);
                }
            }


            if ((false === $realpath) || !(file_exists($realpath)))
                throw new \Sohoa\Framework\Exception('Path ' . $filename . ' (' . (($realpath === false) ? 'false' : $realpath) . ') not found!');

            return $realpath;
        }

        public function render()
        {
            while ($h = array_pop($this->_headers))
                $this->_out->sendHeader($h[0], $h[1], $h[2], $h[3]);

            $this->_out->writeAll($this->renderFile($this->_file));
        }

        public function getHeaders()
        {
            return $this->_headers;
        }

        public function httpHeader($hName, $hValue, $force = true, $status = null)
        {
            $this->_headers[] = array(
                $hName,
                $hValue,
                $force,
                $status
            );
        }

        public function setViewFile($filename)
        {
            $this->_file = $filename;
        }

        public function renderFile($filename)
        {
            $filename                   = $this->getFilenamePath($filename);
            $this->_file                = $filename;
            $this->_inherits[$filename] = array();
            // used by the placeholder

            ob_start("mb_output_handler");
            extract((array) $this->_data);
            include($filename);

            // restore args
            $content = ob_get_contents();
            ob_end_clean();

            while ($inherit = array_pop($this->_inherits[$filename]))
                $content = $this->renderFile($inherit);

            return $content;
        }
    }
}
