<?php

/**
 * Se mettre d'accord sur la doc
 */
namespace Sohoa\Framework {

    use Hoa\Router\Http;

    class Router extends \Hoa\Router\Http {

        const ROUTE_ACTION = 0;

        const ROUTE_VERB = 1;

        const ROUTE_URI_PATTERN = 2;

        private static $_restfulRoutes = array(
            array(self::ROUTE_ACTION => 'index',    self::ROUTE_VERB => 'get',    self::ROUTE_URI_PATTERN => '/'),
            array(self::ROUTE_ACTION => 'show',     self::ROUTE_VERB => 'get',    self::ROUTE_URI_PATTERN => '/<id>'),
            array(self::ROUTE_ACTION => 'new',      self::ROUTE_VERB => 'get',    self::ROUTE_URI_PATTERN => '/new'),
            array(self::ROUTE_ACTION => 'create',   self::ROUTE_VERB => 'post',   self::ROUTE_URI_PATTERN => '/'),
            array(self::ROUTE_ACTION => 'edit',     self::ROUTE_VERB => 'get',    self::ROUTE_URI_PATTERN => '/<id>/edit'),
            array(self::ROUTE_ACTION => 'update',   self::ROUTE_VERB => 'patch',  self::ROUTE_URI_PATTERN => '/<id>'),
            array(self::ROUTE_ACTION => 'destroy',  self::ROUTE_VERB => 'delete', self::ROUTE_URI_PATTERN => '/<id>'),
        );

        public function __construct() {

            parent::__construct();
        }

        public function get($path, $args) {

            $this->addRule($args['as'], array('get'), $path);
        }

        public function post($path, $args) {

            $this->addRule($args['as'], array('post'), $path);
        }

        public function put($path, $args) {

            $this->addRule($args['as'], array('put'), $path);
        }

        public function delete($path, $args) {

            $this->addRule($args['as'], array('delete'), $path);
        }

        public function any($path, $args) {

            $this->addRule($args['as'], array('get', 'post', 'put', 'delete'), $path);
        }

        public function resource($name, $args = array()) {

            $routes = Router::$_restfulRoutes;

            $routes = array_filter($routes, function($route) use(&$args) {

                $accept = true;

                if (isset($args['only'])) {

                    $accept = in_array($route[Router::ROUTE_ACTION], $args['only']);
                }

                if (isset($args['except']) and !isset($args['only'])) {

                    $accept = !in_array($route[Router::ROUTE_ACTION], $args['except']);
                }

                return $accept;
            });

            // write route for each HTTP verb
            foreach($routes as $route) {

                // TODO decide if we pluralize/singularize resource name
                $this->addRule($route[self::ROUTE_ACTION]. ucfirst(strtolower($name)),
                               array($route[self::ROUTE_VERB]),
                               '/' . $name . $route[self::ROUTE_URI_PATTERN],
                               ucfirst(strtolower($name)) . 'Controller',
                               $route[self::ROUTE_ACTION]);
            }
        }
    }
}
