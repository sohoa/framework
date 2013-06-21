<?php
    namespace Sohoa\Framework {
        /**
         * Class Bootstrap
         *
         * @package Sohoa\Framework
         */
        class Bootstrap
        {
            /**
             * @var array
             */
            private $_file = array();

            /**
             * @var Bootstrap
             */
            private static $_instance = null;

            /**
             * @return Bootstrap
             */
            public static function getInstance()
            {
                if (self::$_instance === null)
                    self::$_instance = new Bootstrap();

                return self::$_instance;
            }

            /**
             * @param string      $file
             * @param string      $namespace
             * @param Application $_this
             */
            public function load($file = null, $namespace = '\\', Application &$_this = null)
            {
                if ($file !== null and is_file($file) and !in_array($file, $this->_file)) {
                    require $file;
                    $this->_file[] = $file;
                    $name          = substr($file, strrpos($file, '/'));
                    $name          = substr($name, 0, strpos($name, '.'));
                    $name          = ucfirst($name);
                    $className     = $namespace . $name;
                    $object        = dnew($className, array($_this));
                    $reflection    = new \ReflectionClass($className);
                    $methods       = array();

                    if ($reflection->hasProperty('stack')) {
                        $stack = $reflection->getProperty('stack');
                        if ($stack->isPublic() === true) {
                            $array = $stack->getValue($object);
                            foreach ($array as $method)
                                $methods[] = new \ReflectionMethod($className, $method);

                        }

                    } else {
                        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
                    }

                    foreach ($methods as $method)
                        if ($method->getDeclaringClass()->getName() === $name)
                            $method->invoke($object);


                }
            }


        }
    }
