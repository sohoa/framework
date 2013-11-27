<?php
namespace Sohoa\Framework\Kit {

    class Greut extends Kitable
    {

        protected $_path = 'hoa://Application/View/%s/%s.tpl.php';

        public function setDefaultPath($path = 'hoa://Application/View/%s/%s.tpl.php')
        {

            $old         = $this->_path;
            $this->_path = $path;

            return $old;
        }

        public function getDefaultPath()
        {

            return $this->_path;
        }

        public function render($data = null)
        {

            $controller = null;
            $action     = null;

            if (is_string($data))
                return $this->renderFile($data);

            if (is_array($data)) {
                if (array_key_exists('controller', $data))
                    $controller = $data['controller'];
                if (array_key_exists(0, $data))
                    $controller = $data[0];
                if (array_key_exists('action', $data))
                    $action = $data['action'];
                if (array_key_exists(1, $data))
                    $action = $data[1];
            }

            if ($controller === null or $action === null) {
                $route      = $this->router->getTheRule();
                $controller = $route[4];
                $action     = $route[5];
            }

            return $this->renderRoute(ucfirst($controller), ucfirst($action));
        }

        protected function renderFile($filename)
        {

            $this->view->setViewFile($filename);
            $this->view->render();

            return $filename;
        }

        protected function renderRoute($controller, $action, $path = null)
        {

            if ($path === null)
                $path = $this->getDefaultPath();

            $path = sprintf($path, $controller, $action);

            return $this->renderFile($path);
        }
    }
}
 