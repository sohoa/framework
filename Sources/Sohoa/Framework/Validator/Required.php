<?php
namespace Sohoa\Framework\Validator  {
    class Required extends Validator {

        protected function _valid($data, $arguments)
        {
            return (empty($data) !== true && $data !== null);
        }

        protected function setMessage()
        {
            return 'This field is required';
        }

    }
}