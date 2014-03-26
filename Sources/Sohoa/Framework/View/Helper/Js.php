<?php

namespace Sohoa\Framework\View\Helper {


    class Js extends Resource
    {
        protected $_output = "<script src='%s'></script>\n";
        protected $_extension = '.js';
        public function script($file) {

            $this->store($file);

            return $this;
        }


    }

}
