<?php
namespace Sohoa\Framework\Form\Theme {

    class Generic
    {
        protected $_form = null;
        protected $_errors = array();

        public function setForm($form)
        {
            $this->_form = $form;
        }

        public function getForm()
        {
            return $this->_form;
        }

        public function hasError($name)
        {

            if (array_key_exists($name, $this->_errors) === false) {
                return false;
            }

            foreach ($this->_errors[$name] as $value) {

                if ($value !== null) {
                    return true;
                }
            }

            return false;
        }

        public function setErrors(array $error)
        {
            $this->_errors = $error;
        }

        public function getError($name)
        {
            if ($this->hasError($name) === true) {
                return $this->_errors[$name];
            }

            return null;
        }
    }
}
