<?php
namespace Sohoa\Framework {

    class Validator {

        private static $_instance = null;
        private $_data = array();
        private $_current = '';
        private $_type = 'validator';
        private $_stack = array();

        public static function get($id)
        {
            if(!isset(static::$_instance[$id])){
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

            if($id === null){
                return $this->_data;
            }

            if(isset($this->_data[$id])){
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
            if($key === 'validator')
            {
                $this->_type = 'validator';
            }
            else if ($key === 'filter')
            {
                $this->_type = 'filter';
            }
            else
            {
                $this->_current = $key;
            }

            return $this;
        }

        public function __call($name, $args)
        {
            if($this->_type === 'validator'){
                $instance = dnew('\Sohoa\Framework\Validator\\'.ucfirst($name));

                $instance->setName($this->_current);
                $instance->setData($this->getCurrentData());
                $instance->setArguments($args);
            }
            else if($this->_type === 'filter'){
                $instance =  dnew('\Stdclass');
            }

            $this->_stack[$this->_current][] = ['type' => $this->_type, 'object' => $instance];


            return $this;
        }

        public function getStack($name)
        {
            if(isset($this->_stack[$name])){
                return  $this->_stack[$name];
            }
            return null;
        }








    }

}