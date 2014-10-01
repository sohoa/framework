<?php
namespace Sohoa\Framework\Form\Validate {

    class Email extends Validate
    {
        protected $_detail = 'The given value is not an valid email address';

        protected function _valid($data, $argument)
        {
            if (in_array('getOptions', get_class_methods($this->_parent))) {
                throw new Exception("You cant set Email validator on item %s", 0, array(get_class($this->_parent)));
            }

            return filter_var($data, FILTER_VALIDATE_EMAIL);
        }
    }
}
