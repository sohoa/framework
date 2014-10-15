<?php
namespace Sohoa\Framework\Validator  {
    class Email extends Validator {

        protected function _valid($data, $arguments)
        {

            return (filter_var($data, FILTER_VALIDATE_EMAIL) === $data);

        }

        protected function setMessage()
        {
            return  'The given value is not an valid email address';
        }

    }
}