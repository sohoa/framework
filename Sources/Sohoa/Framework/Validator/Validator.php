<?php
namespace Sohoa\Framework\Validator {
    class Validator {

        protected $_name = '';
        protected $_data = null;
        protected $_arguments = array();


        public function setName($name)
        {
            $this->_name = $name;
        }

        public function setData($data)
        {
            $this->_data = $data;
        }

        public function setArguments(Array $arguments)
        {
            $this->_arguments = $arguments;
        }

        public function getName()
        {
            return $this->_name;
        }

        public function getData()
        {
            return $this->_data;
        }

        public function getArguments()
        {
            return $this->_arguments;
        }

        public function isValid($data = null)
        {

            if($data === null){
               $data = $this->getData();
            }

            return $this->_valid($data, $this->getArguments());
        }

        protected function _valid($data, $arguments) {
            return false;
        }

    }
}