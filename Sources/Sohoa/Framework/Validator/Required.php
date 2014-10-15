<?php
namespace Sohoa\Framework\Validator  {
    class Required extends Validator {

        protected function _valid($data, $arguments)
        {
            return (!empty($data) && $data !== null);
        }

    }
}