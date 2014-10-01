<?php
namespace Sohoa\Framework\Form {

    class Element implements \ArrayAccess
    {
        protected $_name = '';
        protected $_attributes = array();
        protected $_newAttributes = true;
        protected $_child = array();
        protected $_id = null;
        protected $_label = null;
        protected $_parent = null;
        protected $_optionnal = false;
        protected $_need = array();

        public function __call($name, $value)
        {
            $name = str_replace('_', '-', $name);
            if (count($value) > 0) {
                if ($this->_newAttributes === true or array_key_exists($name, $this->_attributes)) {
                    $this->setAttribute($name, $value);
                } else {
                    throw new Exception("You can not add this attribute (%s)", 1, array($name));
                }
            }

            return $this;
        }

        public function setAttribute($name, $value)
        {
            if (is_array($value)) {
                $value = array_shift($value);
            }

            $this->_attributes[$name] = $value;
        }

        public function label($text)
        {
            $this->_label = $text;

            return $this;
        }
        public function id($id)
        {
            $this->_id = $id;
            $this->_attributes['id'] = $id;
            $this->name($id);

            return $this;
        }

        public function setParent(Element $parent)
        {
            $this->_parent = $parent;
        }

        public function getAttributes()
        {
            return $this->_attributes;
        }

        public function offsetExists($offset)
        {
            return array_key_exists($offset, $this->_child);
        }

        public function offsetGet($offset)
        {
            return $this->_child[$offset];
        }

        public function offsetSet($offset, $value)
        {
            if ($offset === null) {
                if (is_object($value) === true) {
                    if (($s = $value->getAttribute('name')) !== null) {
                        $this->_child[$s] = $value;

                        return;
                    }
                }
                        $this->_child[] = $value;
            } else {
                $this->_child[$offset] = $value;
            }
        }

        public function offsetUnset($offset)
        {
            return;
        }

        public function insertBeforeLast($string)
        {
            $last           = array_pop($this->_child);
            $this->_child[] = $string;
            $this->_child[] = $last;
        }

        public function getChilds()
        {
            return $this->_child;
        }

        public function getName()
        {
            return $this->_name;
        }

        public function getId()
        {
            return $this->_id;
        }

        public function getLabel()
        {
            return $this->_label;
        }

        public function getAttributeAsString()
        {
            $out = array();

            foreach ($this->_attributes as $name => $value) {
                if ($value !== null) {
                    $out[] = sprintf('%s="%s"', $name, $value);
                }
            }

            return implode(' ', $out);
        }

        public function extractAttribute($name)
        {
            $a = null;

            if (array_key_exists($name, $this->_attributes)) {
                $a = $this->_attributes[$name];
                unset($this->_attributes[$name]);
            }

            return $a;
        }

        public function getAttribute($name)
        {
            if (array_key_exists($name, $this->_attributes)) {
                return $this->_attributes[$name];
            }

            return null;
        }

        public function defaultAttribute($name, $value)
        {
            $attr = $this->getAttribute($name);

            if ($attr === null) {
                $this->setAttribute($name, $value);
            }
        }

        public function need($string)
        {
            $array           = ['_'];
            $string          = str_replace($array, '|', $string);
            $array           = explode('|', $string);
            $this->_need     = array_merge($array, $this->_need);

            return $this;
        }

        public function praspel($string)
        {
            return $this->need('praspel:'.$string);
        }

        public function optionnal()
        {
            $this->_optionnal = true;

            return $this;
        }

        public function isOptionnal()
        {
            return $this->_optionnal;
        }

        public function getNeed()
        {
            return $this->_need;
        }

        public function getParent()
        {
            return $this->_parent;
        }
    }
}
