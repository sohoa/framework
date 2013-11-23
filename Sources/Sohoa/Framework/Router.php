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

		const ROUTE_GENERIC = 'generic';

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

        /**
         * Return true if the given route contains (?<controller>) and (?<action>)
         * @param string $route
         * @return boolean
         */
        protected static function isGenericRoute($route) {

            return false !== preg_match('#(?=.*\(\?<controller\>.+\))(?=.*\(\?<action\>.+\)).+#', $route);
        }

        public function __call($name, $arguments) {

            // private rules added by Hoa\Xyl should be handle by Hoa\Router itself
            if('_' == $name[0]) {

                return parent::__call($name, $arguments);
            }

            if($name == 'any') {

                $methods = self::$_methods;
            } else {

                $methods = array($name);
            }

            if (count($arguments) === 1 && self::isGenericRoute($arguments[0])) {
                $arguments = array(self::ROUTE_GENERIC, $methods, $arguments[0], 'controller', 'action');
            } else {

                $args = $arguments[1];

                if(!isset($args['as'])) {

                    throw new Exception('Missing as !');
                }

                if(!isset($args['to'])) {

                    throw new Exception('Missing to !');
                }

                $to = explode('#', $args['to']);
                $call = $to[0];
                $able = $to[1];

                $arguments = array($args['as'], $methods, $arguments[0], $call, $able);
            }
            return call_user_func_array(array($this, 'addRule'), $arguments);
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
