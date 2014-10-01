<?php
namespace Sohoa\Framework\Form {
    class Select extends Element
    {
        protected $_name = 'select';
        protected $_options = array();

        public function option($value , $name = null)
        {
            $this->_options[] = [$value , $name];

            return $this;
        }

        public function getOptions()
        {
            return $this->_options;
        }

    }
}
