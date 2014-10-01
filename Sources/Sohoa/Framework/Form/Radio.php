<?php
namespace Sohoa\Framework\Form {

    class Radio extends Select
    {
        protected $_name = 'input';
        protected $_attributes = ['type' => 'radio'];

        public function option($value, $label)
        {
            $this->_options[] = [$value , $label];

            return $this;
        }
    }
}
