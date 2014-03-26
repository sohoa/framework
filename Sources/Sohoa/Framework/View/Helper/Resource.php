<?php

namespace Sohoa\Framework\View\Helper {

    use Sohoa\Framework\View;

    abstract class Resource extends View\Helper
    {
        protected static $_useMin = false;
        protected static $_append = '?date=%s';
        protected static $_appendData = null;
        protected $_output = '';
        protected $_extension = '';
        protected $_store = array();
        protected $_resource = array();

        public static function useMin()
        {
            self::$_useMin = true;
        }

        public static function cache($data , $string = null){

            if($string !== null)
                self::$_append = $string;

            self::$_appendData = $data;
        }

        protected function store($file) {

            if(self::$_appendData !== null)
                $file .= sprintf(self::$_append , self::$_appendData);

            array_push($this->_store, $file);
        }

        public function min($file = null){

            if($file === null){
                $file = '';
                foreach ($this->_store as $value)
                    $file .= $value;


                $file = md5($file).$this->_extension;
            }

            if(self::$_appendData !== null)
                $file .= sprintf(self::$_append , self::$_appendData);

            $this->_resource[$file]    = $this->_store;
            $this->_store               = array();

            return $this;
        }

        protected function _html($file){
            return sprintf($this->_output , $file);
        }

        public function html()
        {
           $out = '';

            foreach ($this->_resource as $min => $files) 
                if(self::$_useMin === true)
                    $out .= $this->_html($min);
                else
                    foreach ($files as $file)   
                        $out .= $this->_html($file);
                
            

            foreach ($this->_store as $file)
                $out .= $this->_html($file);
            

           return $out;

        }
        public function __toString()
        {
            return htmlentities($this->html());
        }

    }

}
