<?php
namespace Sohoa\Framework\Form\Validate {

    class Validate
    {
        protected $_detail = '';
        protected $_parent = null;
        protected $_form   = null;
        protected $_errors = null;

        public function valid($data, $argument, $parent, $form = false)
        {
            $this->_form   = $form;
            $this->_parent = $parent;
            $bool = $this->_valid($data, $argument);
            if ($bool === false) {
                $this->_errors = $this->getDetail();
            }

            return $bool;
        }

        public function getErrors()
        {
            return $this->_errors;
        }

        protected function getDetail()
        {
            return $this->_detail;
        }

        protected function _valid($data, $element)
        {
            throw new Exception("You must implements your own function", 0);
        }
    }
}
