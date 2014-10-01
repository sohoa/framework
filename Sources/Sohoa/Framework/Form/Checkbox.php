<?php
namespace Sohoa\Framework\Form {

    class Checkbox extends Select
    {
        protected $_name = 'input';
        protected $_attributes = ['type' => 'checkbox'];

        public function option($value, $label, $name)
        {
            $this->_options[] = [$value , $label ,$name];

            return $this;
        }

        public function name()
        {
            throw new Exception("Can not define an name or id of checkbox", 0);
        }

        public function validate()
        {
            throw new Exception("Can not define an validator on checkbox", 1);
        }

        public function getAllName()
        {
            $o = array();

            foreach ($this->getOptions() as $value) {
                $o[] = $value[2];
            }

            return $o;
        }
    }
}
