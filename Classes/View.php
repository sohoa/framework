<?php

/**
 * Se mettre d'accord sur la doc.
 */

namespace Sohoa\Framework {

    use Hoa\View\Viewable;
    use Hoa\Http\Response;
    use Hoa\Router\Http;

    class View implements \Hoa\View\Viewable
    {

        protected $_in = null;
        protected $_out = null;
        protected $_data = null;
        protected $_router = null;

        public function __construct(
            $in, \Hoa\Stream\IStream\Out $out, \Hoa\Router $router = null, \StdClass $data = null)
        {

            if (null === $data)
                $data = new \StdClass();

            $this->_in = $in;
            $this->_out = $out;
            $this->_data = $data;
            $this->_router = $router;

            return;
        }

        public function getOutputStream()
        {

            return $this->_out;
        }

        public function getData()
        {

            return $this->_data;
        }

        public function render()
        {

            $data = $this->getData();
            $router = $this->getRouter();

            ob_start();
            require $this->_in;
            $content = ob_get_contents();
            ob_end_clean();

            $this->getOutputStream()->writeAll($content);

            return;
        }

        public function getRouter()
        {

            return $this->_router;
        }

        public function import($in, $data = null)
        {

            $new = new static(
                $in, $this->getOutputStream(), $this->getRouter(), $data
            );
            $new->render();

            return;
        }

    }

}
