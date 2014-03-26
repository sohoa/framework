<?php

/**
 * Se mettre d'accord sur la doc.
 */

namespace Sohoa\Framework\View {

    use Hoa\Http\Response\Response;
    use Hoa\Router\Router;
    use Hoa\Stream\IStream\Out;
    use Hoa\View\Viewable;
    use Sohoa\Framework\Exception;
    use Sohoa\Framework\Framework;
    use Sohoa\Framework\Kit;
    use Sohoa\Framework\View\Helper as Helper;

    class Greut implements Viewable, Soview
    {


        protected $_out = null;
        protected $_router = null;
        protected $_framework = null;
        protected $_data = null;
        protected $_paths = null;
        protected $_inherits = array();
        protected $_blocks = array();
        protected $_blocknames = array();
        protected $_file = '';
        protected $_headers = array();
        protected $_helpers = array();

        public function __construct(Out $response = null)
        {

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
            return $this->_router;
        }

        public function setRouter(Router $router)
        {
            $this->_router = $router;

            return $this;
        }

        public function setFramework(Framework $framework)
        {
            $this->_framework = $framework;
            $framework->kit('greut', new Kit\Greut());

            return $this;
        }


        public function setPath($path)
        {
            if ($path[strlen($path) - 1] !== '/')
                $path .= '/';

            $this->_paths = $path;

            return $this;
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

        public function __call($function , $argument){


            return $this->$function->$function($argument[0]);
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
            if (preg_match('#^(?:[/\\\\]|[\w]+:([/\\\\])\1?)#', $filename) !== 1)
                $filename = $this->_paths . $filename;


            $realpath = realpath(resolve($filename, false)); // We need to use resolve beacause realpath dont use stream wrapper

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

            return $this;
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
