<?php
namespace Sohoa\Framework\Form {
    class Select extends Element
    {
        protected $_name = 'select';
        protected $_parent = null;
        protected $_options = array();

        public function option($value , $name = null, $args = array())
        {
            if($this->_parent === null){
                $this->_options[] = [$value , $name, $args];
            }
            else {
                $this->_options[$this->_parent][] = [$value , $name, $args];
            }

            return $this;
        }

        public function getOptions()
        {
            return $this->_options;
        }

        public function group($label)
        {
            $this->_parent = $label;

            return $this;
        }

        public function root()
        {
            $this->_parent = null;

            return $this;
        }

    }
}
