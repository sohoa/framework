<?php
namespace Sohoa\Framework\Form\Validate {

    class Min extends Validate
    {
        protected $_detail = 'The given value is not long enough, need >= %d char';
        protected $min = 0;

        protected function _valid($data, $argument)
        {
            if (in_array('getOptions', get_class_methods($this->_parent))) {
                throw new Exception("You cant set Min validator on item %s", 0, array(get_class($this->_parent)));
            }
            $this->min = array_shift($argument);

            return (intval($data) >= intval($this->min));
        }

        protected function getDetail()
        {
            return sprintf($this->_detail, $this->min);
        }
    }
}
