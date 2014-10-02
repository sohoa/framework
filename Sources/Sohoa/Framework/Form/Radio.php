<?php
namespace Sohoa\Framework\Form {

    class Radio extends Select
    {
        protected $_name = 'input';
        protected $_attributes = ['type' => 'radio'];

        public function option($value, $label, $args = array())
        {
            $this->_options[] = [$value , $label, $args];

            return $this;
        }
    }
}
