<?php
namespace Sohoa\Framework\Form\Validate {

    class Max extends Validate
    {
        protected $_detail = 'The given value is too long, need <= %s char';
        protected $max = 0;

        protected function _valid($data, $argument)
        {
            if (in_array('getOptions', get_class_methods($this->_parent))) {
                throw new Exception("You cant set Max validator on item %s", 0, array(get_class($this->_parent)));
            }
            $this->max = array_shift($argument);

            return (intval($data) <= intval($this->max));
        }

        protected function getDetail()
        {
            return sprintf($this->_detail, $this->max);
        }
    }
}
