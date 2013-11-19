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
        private static $_path = 'hoa://Application/Config/Environnement/';
        private $_current = null;
        private $_variable = null;

        public function __construct($environnement)
        {
            Framework::services('environnement', $this);

            $this->_current = strtolower($environnement);
            $general        = static::$_path . 'Configuration.php';
            $env            = static::$_path . ucfirst($environnement) . '/Application.php';

            if (file_exists($general)) {
                $var = require_once $general;
                $this->setVariables($var);
            }

            if (file_exists($env)) {
                $var = require_once $env;
                $this->setVariables($var);
            }
        }

        public function getEnvironnement()
        {
            return $this->_current;
        }

        public static function setPath($path)
        {
            self::$_path = $path;
        }

        public function setVariables(Array $array)
        {
            foreach ($array as $key => $value)
                $this->offsetSet($key, $value);
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
