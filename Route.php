<?php
namespace {
    from('Hoa')
        ->import('Router.Http');
}

/**
 * Se mettre d'accord sur la doc
 */
namespace Sohoa {

    class Route extends \Hoa\Router
    {

        private $_route = null;

        public function __construct()
        {
            $this->_route = new \Hoa\Router\Http();
        }

        public function get()
        {
        }

        public function post()
        {
        }

        public function put()
        {
        }

        public function delete()
        {
        }

        public function any()
        {
        }

        public function controller()
        {
        }
    }

}