<?php

namespace Sohoa\Framework\View\Helper {

    use Sohoa\Framework\View;

    class Resource extends View\Helper
    {
        protected static $_useMin = false;
        protected static $_id = 'z__current';
        public static $_css = "<link href='%s' rel='stylesheet' />\n";
        public static $_js = "<script src='%s'></script>\n";

        protected $_tree = array();

        public static function useMin()
        {
            static::$_useMin = true;

        }

        public function css($path)
        {
            $this->_tree['css'][static::$_id][] = $path;

            return $this;
        }

        public function min($path , $type = 'auto')
        {
            if ($type === 'auto') {

                if(preg_match('#js$#', $path))
                    $type = 'js';

                if(preg_match('#css$#', $path))
                    $type = 'css';

            }

            switch ($type) {
                case 'css':
                    $this->_minCss($path);
                    break;
                case 'js':
                    $this->_minJs($path);
                    break;
            }

            return $this;
        }

        protected function _minCss($path)
        {
            $this->_tree['css'][$path] = $this->_tree['css'][static::$_id];
            $this->_tree['css'][static::$_id] = array();
        }

         protected function _minJs($path)
         {
            $this->_tree['js'][$path] = $this->_tree['js'][static::$_id];
            $this->_tree['js'][static::$_id] = array();
        }

        public function js($path)
        {
            $this->_tree['js'][static::$_id][] = $path;

            return $this;
        }

        protected function _htmlCss()
        {
            ksort($this->_tree['css']);
            $out = '';

            if(static::$_useMin === true)
                foreach (array_keys($this->_tree['css']) as $min)
                    if($min !== static::$_id)
                        $out .= sprintf(static::$_css , $min);

            foreach ($this->_tree['css'] as $id => $v)
                if(static::$_useMin === false or $id === static::$_id)
                    foreach ($v as $css)
                        $out .= sprintf(static::$_css , $css);

            return $out;
        }

        protected function _htmlJs()
        {
            ksort($this->_tree['js']);
            $out = '';

            if(static::$_useMin === true)
                foreach (array_keys($this->_tree['js']) as $min)
                    if($min !== static::$_id)
                        $out .= sprintf(static::$_js , $min);

            foreach ($this->_tree['js'] as $id => $v)
                if(static::$_useMin === false or $id === static::$_id)
                    foreach ($v as $js)
                        $out .= sprintf(static::$_js , $js);

            return $out;
        }

        public function html()
        {
           return $this->_htmlCss() ."\n" . $this->_htmlJs();

        }
        public function __toString()
        {
            return htmlentities($this->html());
        }

    }

}
