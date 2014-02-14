<?php
/**
 * Created by PhpStorm.
 * User: Camael24
 * Date: 10/01/14
 * Time: 09:43
 */
namespace Sohoa\Framework\Router {
    use Sohoa\Framework\Router;

    class Resource
    {

        protected  $_router = null;
        protected  $_restRule = array();
        protected  $_resourceTree = array();

        public function __construct($resource, $argument, Router $router, Array $restRules = array())
        {
            $this->_router = $router;
            $this->_restRule = $restRules;

            $this->resource($resource, $argument);
        }

        public function resource($resource, $argument = array())
        {

            $parentResource = array();
            if (!empty($this->_resourceTree)) {
                $last = count($this->_resourceTree) - 1;
                $parentResource = $this->getResourceData($this->_resourceTree[$last]);
            }

            $this->generateResource($resource, $argument, $parentResource);

            return $this;
        }

        protected function getResourceData($resource)
        {
            $route = array();
            foreach ($this->_restRule as $v) {
                $rAction = $this->resourceAction($resource, 'show');
                if ($this->_router->ruleExists($rAction)) {
                    $rule = $this->_router->getRule($rAction);
                    $route[$v[Router::ROUTE_ACTION]] = $rule[3];
                } else {
                    throw new \Sohoa\Framework\Exception('You must declare the resource with action "show" for use children');
                }
            }

            return $route;
        }

        protected function resourceAction($resource, $action)
        {
            return $action . ucfirst(strtolower($resource));
        }

        protected function generateResource($name, $args = array(), $parent = array())
        {

            $this->_resourceTree[] = $name;
            $routes = $this->_restRule;
            $varName = (isset($args['variable'])) ? $args['variable'] : strtolower($name) . '_id';
            $routes = array_filter($routes, function ($route) use (&$args, &$parent) {
                $accept = true;

                if (isset($args['only'])) {

                    $accept = in_array($route[Router::ROUTE_ACTION], $args['only']);
                }

                if (isset($args['except']) and !isset($args['only'])) {

                    $accept = !in_array($route[Router::ROUTE_ACTION], $args['except']);
                }

                return $accept;
            });

            array_walk($routes, function (&$route, $key, $p) {
                list($parent, $name) = $p;

                if (array_key_exists($route[Router::ROUTE_ACTION], $parent))
                    $route[Router::ROUTE_URI_PATTERN] = $parent[$route[Router::ROUTE_ACTION]] . '/' . $name . $route[Router::ROUTE_URI_PATTERN];
                else
                    $route[Router::ROUTE_URI_PATTERN] = '/' . $name . $route[Router::ROUTE_URI_PATTERN];

            }, array($parent, $name));

            // write route for each HTTP verb
            foreach ($routes as $route) {

                // TODO decide if we pluralize/singularize resource name

                if (!$this->_router->ruleExists($this->resourceAction($name, $route[Router::ROUTE_ACTION])))
                    $this->_router->addRule($this->resourceAction($name, $route[Router::ROUTE_ACTION]),
                        array($route[Router::ROUTE_VERB]),
                        sprintf($route[Router::ROUTE_URI_PATTERN], $varName),
                        ucfirst(strtolower($name)),
                        $route[Router::ROUTE_ACTION]);
            }
        }

    }
}
