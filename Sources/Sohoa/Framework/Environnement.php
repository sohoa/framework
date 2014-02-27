<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 19/11/13
 * Time: 09:42
 */
namespace Sohoa\Framework {
    class Environnement implements \ArrayAccess
    {
        private static $_path = 'hoa://Application/Config/';
        private $_current = null;
        private $_variable = null;
        private $_framework = null;

        public function __construct($framework, $environnement = 'production')
        {
            $this->_current   = strtolower($environnement);
            $general          = static::$_path . 'Environnement.php';
            $env              = static::$_path . ucfirst($environnement) . '/Application.php';
            $this->_framework = $framework;

            if (file_exists($general)) {
                $var = require_once $general;
                $this->setVariables($var);
            }

            if (file_exists($env)) {
                $var = require_once $env;
                $this->setVariables($var);
            }
        }

        public function getFramework()
        {
            return $this->_framework;
        }

        public function getEnvironnement()
        {
            return $this->_current;
        }

        public static function setPath($path)
        {
            self::$_path = $path;
        }

        public function setVariables($array)
        {
            if (is_array($array))
                foreach ($array as $key => $value)
                    $this->offsetSet($key, $value);

            return $this;
        }

        public function offsetExists($offset)
        {
            return array_key_exists($offset, $this->_variable);
        }

        public function offsetGet($offset)
        {
            return $this->_variable[$offset];
        }

        public function offsetSet($offset, $value)
        {
            $this->_variable[$offset] = $value;
        }

        public function offsetUnset($offset)
        {
            unset($this->_variable[$offset]);
        }
    }
}
