<?php
namespace Sohoa\Framework\Validator {
    class Validator {

        protected $_name = '';
        protected $_data = null;
        protected $_arguments = array();
        private $_message = null;

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

        public function isValid($data = null, $arguments = null)
        {
            $this->_message = null;
            $data           = ($data === null) ? $this->getData() : $data;
            $arguments      = ($arguments === null) ?$this->getArguments() : $arguments;
            $valid          = $this->_valid($data, $arguments);

            if($valid === false)
            {
                $this->_message = $this->setMessage();
            }

            return $valid;
        }

        protected  function setMessage()
        {
            throw new Exception("You must implements your own function", 0);
        }

        public function getMessage()
        {
            return $this->_message;
        }

        protected function _valid($data, $arguments)
        {
             throw new Exception("You must implements your own function", 0);
        }

    }
}