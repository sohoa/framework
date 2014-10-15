<?php
namespace Sohoa\Framework\Validator  {
    class Range extends Validator {

        protected function _valid($data, $arguments)
        {
            if(count($arguments) === 1 and isset($arguments[0])){

                return in_array($data, $arguments[0]);
            }

            throw new Exception("Need only one argument", 1);

        }

        protected function setMessage()
        {
            return  'This field are not in authorized option';
        }

    }
}