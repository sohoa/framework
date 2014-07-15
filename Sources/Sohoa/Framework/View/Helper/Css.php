<?php

namespace Sohoa\Framework\View\Helper {


    class Css extends Resource
    {
        protected $_output = "<link href='%s' rel='stylesheet' />\n";
        protected $_extension = '.css';
       

        public function css($link)
        {
            $this->store($link);

            return $this;
        }

       

    }

}
