<?php
namespace Sohoa\Framework\Form\Validate {

    class Check
    {
        private $_isValid = null;
        private $_id = null;
        private $_errors = array();

        public function __construct(\Sohoa\Framework\Form\Form $form = null)
        {
            if ($form !== null) {
                $this->setId($form->getFormId());
            }

        }

        public function setId($id)
        {
            $this->_id = $id;
        }

        public function isValid(array $data = array())
        {
                $this->_errors  = array();

                return $this->validate($data);
        }

        protected function validate(array $data = array())
        {
            $form  = \Sohoa\Framework\Form\Form::get($this->_id);
            $valid = true;

            if (empty($data)) {
                $data = $form->getData();
            }

            foreach ($form->getChilds() as $child) {
                if (is_object($child)) {

                    $name  = $child->getAttribute('name');
                    $iData = (array_key_exists($name, $data)) ? $data[$name] : null;

                    if ($child->isOptionnal() === true and (strlen($iData) === 0)) {
                            return true;
                    }

                    foreach ($child->getNeed() as $val) {
                        if ($this->valid($val, $child, $iData, $form) === false) {
                            $valid = false;
                        }
                    }
                }
            }

            return $valid;
        }

        private function valid($validator, $item, $data, $form)
        {
            $validator              = explode(':', $validator);
            $val                    = array_shift($validator);
            $instance                = dnew('\\Sohoa\\Framework\\Form\\Validate\\'.ucfirst($val));
            $bool                   = $instance->valid($data, $validator, $item, $form);
            $name                   = $item->getAttribute('name');

            if($instance->getErrors() !== null)
                $this->_errors[$name][] = $instance->getErrors();

            return $bool;
        }

        public function getErrors()
        {
            return $this->_errors;
        }

        public function setErrors(array $error)
        {
            $this->_errors = $error;
        }
    }
}
