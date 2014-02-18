<?php

/**
 * Se mettre d'accord sur la doc
 */
namespace Sohoa\Framework {

    use Hoa\Router\Http;
    use Sohoa\Framework\Router\IRouter;
    use Sohoa\Framework\Router\Resource;

    class Router extends Http implements IRouter
    {
        const ROUTE_ACTION = 0;
        const ROUTE_VERB = 1;
        const ROUTE_URI_PATTERN = 2;
        const ROUTE_GENERIC = 'generic';

        const REST_INDEX = 0;

        const REST_NEW = 1;

        const REST_SHOW = 2;

        const REST_CREATE = 3;

        const REST_EDIT = 4;

        const REST_UPDATE = 5;

        const REST_DESTROY = 6;

        protected  static $_restfulRoutes = array(
            self::REST_INDEX   => array(self::ROUTE_ACTION => 'index', self::ROUTE_VERB => 'get', self::ROUTE_URI_PATTERN => '/'),
            self::REST_NEW     => array(self::ROUTE_ACTION => 'new', self::ROUTE_VERB => 'get', self::ROUTE_URI_PATTERN => '/new'),
            self::REST_SHOW    => array(self::ROUTE_ACTION => 'show', self::ROUTE_VERB => 'get', self::ROUTE_URI_PATTERN => '/(?<%s>[^/]+)'),
            self::REST_CREATE  => array(self::ROUTE_ACTION => 'create', self::ROUTE_VERB => 'post', self::ROUTE_URI_PATTERN => '/'),
            self::REST_EDIT    => array(self::ROUTE_ACTION => 'edit', self::ROUTE_VERB => 'get', self::ROUTE_URI_PATTERN => '/(?<%s>[^/]+)/edit'),
            self::REST_UPDATE  => array(self::ROUTE_ACTION => 'update', self::ROUTE_VERB => 'patch', self::ROUTE_URI_PATTERN => '/(?<%s>[^/]+)'),
            self::REST_DESTROY => array(self::ROUTE_ACTION => 'destroy', self::ROUTE_VERB => 'delete', self::ROUTE_URI_PATTERN => '/(?<%s>[^/]+)'),
        );

        protected $_framework = null;

        public function construct()
        {
            if (file_exists('hoa://Application/Cache/Route.php')) {
                $this->loadCache('hoa://Application/Cache/Route.php');
            } else {
                if (file_exists('hoa://Application/Config/Route.php')) {
                    require_once 'hoa://Application/Config/Route.php';
                }
            }
        }

        public function setFramework(Framework $framework)
        {
            $this->_framework = $framework;

            return $this;
        }

        public function getFramework()
        {
            return $this->_framework;
        }

        /**
         * Return true if the given route contains (?<controller>) and (?<action>)
         * @param string $route
         * @return boolean
         */
        protected static function isGenericRoute($route)
        {
            return false !== preg_match('#(?=.*\(\?<controller\>.+\))(?=.*\(\?<action\>.+\)).+#', $route);
        }

        public function __call($name, $arguments)
        {
            // private rules added by Hoa\Xyl should be handle by Hoa\Router itself
            if ('_' == $name[0]) {
                return parent::__call($name, $arguments);
            }

            if ($name == 'any') {
                $methods = static::$_methods;
            } else {
                $methods = array($name);
            }

            if (count($arguments) === 1 && static::isGenericRoute($arguments[0])) {
                $arguments = array(static::ROUTE_GENERIC, $methods, $arguments[0], 'controller', 'action');
            } else {
                $args = $arguments[1];

                if (!isset($args['as'])) {
                    throw new Exception('Missing as !');
                }

                if (!isset($args['to'])) {
                    throw new Exception('Missing to !');
                }

                $to   = explode('#', $args['to']);
                $call = $to[0];
                $able = $to[1];

                $arguments = array($args['as'], $methods, $arguments[0], $call, $able);
            }

            return call_user_func_array(array($this, 'addRule'), $arguments);
        }

        public function setVariable($name, $value)
        {
            $this->_rule[static::RULE_VARIABLES][$name] = $value;

            return $this;
        }

        public function resource($name, $args = array())
        {
            return new Resource($name, $args, $this);
        }

        public function addResourceRule($action, $verb, $uri)
        {
            $last = count(static::$_restfulRoutes);

            static::$_restfulRoutes[$last] = array(
                static::ROUTE_ACTION      => $action,
                static::ROUTE_VERB        => $verb,
                static::ROUTE_URI_PATTERN => $uri,
            );

            return $last;
        }

        public function setResource($id, $action = null, $verb = null, $uri = null)
        {
            if (array_key_exists($id, static::$_restfulRoutes)) {
                $rest                                               = static::$_restfulRoutes[$id];
                static::$_restfulRoutes[$id][static::ROUTE_ACTION]      = ($action === null) ? $rest[static::ROUTE_ACTION] : $action;
                static::$_restfulRoutes[$id][static::ROUTE_VERB]        = ($verb === null) ? $rest[static::ROUTE_VERB] : $verb;
                static::$_restfulRoutes[$id][static::ROUTE_URI_PATTERN] = ($uri === null) ? $rest[static::ROUTE_URI_PATTERN] : $uri;
            }

            return $this;
        }

        public function getResource($id)
        {
            if (array_key_exists($id, static::$_restfulRoutes)) {
                return static::$_restfulRoutes[$id];
            }

            return;
        }

        public function getResources()
        {
            return static::$_restfulRoutes;
        }

        public function dump()
        {
            $out = array();

            foreach ($this->getRules() as $id => $value) {
                $out[] = array($id, implode(',', $value[2]), $value[3], $value[4].'#'.$value[5]);
            }

            return $out;
        }

        public function load(array $rules)
        {
            foreach ($rules as $rule) {
                $methods = explode(',', $rule[1]);
                list($call, $able) = explode('#', $rule[3]);
                $this->addRule($rule[0], $methods, $rule[2], $call, $able);
            }
        }

        public function loadCache($cacheFile)
        {
            $dump = require_once $cacheFile;
            $this->load($dump);
        }

        public function saveCache($cacheFile)
        {
            file_put_contents($cacheFile, '<?php return '.var_export($this->dump(), true).';');
        }

        protected function _unroute($id, $pattern, array $variables,
                                    $allowEmpty = true)
        {
            $variables = array_map('rawurlencode', $variables);

            return parent::_unroute($id, $pattern, $variables, $allowEmpty);
        }
    }
}
