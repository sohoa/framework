<?php
namespace Sohoa\Framework\Validator  {
    class Value  extends Validator  {

        protected function _valid($data, $arguments)
        {
            $this->data = intval($data);
            $this->min  = (isset($arguments[0]) and $arguments[0] !== null) ? $arguments[0] : -PHP_INT_MAX;
            $this->max  = (isset($arguments[1]) and $arguments[1] !== null) ? $arguments[1] : PHP_INT_MAX;

            return ($this->data >= $this->min && $this->data <= $this->max);

        }

        protected function setMessage()
        {

            return sprintf('The given value is not valid, need >= %s and <= %s integer given %s' , $this->min , $this->max, $this->data);
        }

    }
}