<?php
namespace Sohoa\Framework\Form\Validate {

    class Required extends Validate
    {
        protected $_detail = 'This field is required';
        protected function _valid($data, $argument)
        {

            if (in_array('getAllName', get_class_methods($this->_parent))) {

                $name = $this->_parent->getAllName();
                $d    = array();

                foreach ($name as $value) {
                    if (($v = $this->_form->getData($value)) !== null) {
                        $d[$value] = $v;
                    }
                }

                return !empty($d);

            }

            return !empty($data);
        }
    }
}
