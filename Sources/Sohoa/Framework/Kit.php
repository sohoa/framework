<?php
namespace Sohoa\Framework {

    use Sohoa\Framework\Kit\Kitable;

    class Kit extends \Hoa\Dispatcher\Kit
    {
        static protected  $_kits = array();
        static protected  $_kitInit = array();

        public static function add($name, Kitable $instance)
        {
            if (in_array($name, static::$_kitInit)) {
                $key = array_keys(static::$_kitInit, $name);
                $key = $key[0];

                unset(static::$_kitInit[$key]);
            }

            static::$_kits[$name] = $instance;
        }

        public static function set(Array $array)
        {
            foreach ($array as $name => $instance)
                static::add($name, $instance);
        }

        public function getAllKits()
        {
            return static::$_kits;
        }

        protected function init($name, Kitable $instance)
        {
            if (!in_array($name, static::$_kitInit)) {
                $instance->setRouter($this->router);
                $instance->setView($this->view);
                static::$_kitInit[] = $name;
            }

            return $instance;
        }

        public function kit($name)
        {
            if (array_key_exists($name, static::$_kits))
                if (in_array($name, static::$_kitInit))
                    return static::$_kits[$name];
                else
                    return $this->init($name, static::$_kits[$name]);
            else
                throw new Exception('You must add the kit with \Sohoa\Framework\Kit::add() before use it');
        }

        public function __get($key)
        {
            if (array_key_exists($key, static::$_kits)) {
                return $this->kit($key);
            }

            return \Sohoa\Framework\Framework::services($key);
        }
    }
}
