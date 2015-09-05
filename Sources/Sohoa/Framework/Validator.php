<?php
namespace Sohoa\Framework {

    class Validator
    {
        private static $_instance = null;
        private $_data = array();
        private $_current = '';
        private $_type = 'validator';
        private $_stack = array();
        private $_errors = array();

        public static function get($id)
        {
            if (!isset(static::$_instance[$id])) {
                static::$_instance[$id] = new Validator();
            }

            return static::$_instance[$id];
        }

        public function setData($data)
        {
            $this->_data = $data;
        }

        public function getData($id = null)
        {

            if ($id === null) {
                return $this->_data;
            }

            if (isset($this->_data[$id])) {
                return $this->_data[$id];
            }

            return null;
        }

        public function getCurrentData()
        {
            return $this->getData($this->_current);
        }

        public function __get($key)
        {
            if ($key === 'validator') {
                $this->_type = 'validator';
            } elseif ($key === 'filter') {
                $this->_type = 'filter';
            } else {
                $this->_current = $key;
            }

            return $this;
        }

        public function __call($name, $args)
        {
            if ($this->_type === 'validator') {
                $instance = dnew('\Sohoa\Framework\Validator\\'.ucfirst($name));
                $instance->setData($this->getCurrentData());
                $instance->setName($this->_current);
                $instance->setArguments($args);
            } elseif ($this->_type === 'filter') {
                $instance =  dnew('\Stdclass');
            }

            $this->_stack[$this->_current][] = ['type' => $this->_type, 'object' => $instance, 'error' => null];

            return $this;
        }

        public function getStack($name)
        {
            if (isset($this->_stack[$name])) {
                return  $this->_stack[$name];
            }

            return null;
        }

        public function isValid($data = null)
        {
            $data           = ($data === null) ? $this->getData() : $data;
            $this->_errors  = array();

            $f = function ($key) use (&$data) {
                if (isset($data[$key])) {
                    return $data[$key];
                }

                return null;
            };

            $valid = true;

            foreach ($this->_stack as $name => $element) {
                foreach ($element as $i => $validate) {
                    $v = $validate['object']->isValid($f($name));
                    if ($v === false) {
                        $this->_errors[$name][] = ['class' => get_class($validate['object']), 'message' => $validate['object']->getMessage()];
                        $valid = false;
                    }
                }
            }

            return $valid;
        }

        public function getErrors()
        {
            return $this->_errors;
        }

        public function getError($key)
        {
            if (isset($this->_errors[$key])) {
                return $this->_errors[$key];
            }

            return null;
        }

    }

}
