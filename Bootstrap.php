<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Julien
     * Date: 21/06/13
     * Time: 14:12
     * To change this template use File | Settings | File Templates.
     */
    namespace Sohoa\Framework {
        class Bootstrap
        {
            private $_file = array();

            private static $_instance = null;

            public static function getInstance()
            {
                if (self::$_instance === null)
                    self::$_instance = new Bootstrap();

                return self::$_instance;
            }

            public function load($file = null, $namespace = '\\' , Application &$_this)
            {
                if ($file !== null and is_file($file) and !in_array($file, $this->_file)) {
                    require $file;
                    $this->_file[] = $file;
                    $name          = substr($file, strrpos($file, '/'), strrpos($file, '.'));
                    $name          = ucfirst($name);
                    $className     = $namespace . $name;
                    $object        = dnew($className , array($_this));
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
