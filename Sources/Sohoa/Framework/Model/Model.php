<?php
namespace Sohoa\Framework\Model {

    class Model extends ActiveRecord implements \ArrayAccess
    {
        const UPDATE_ALL_VALUE = 'all';  // Only in manu mode
        const UPDATE_CHANGE_VALUE = 'changed';
        const SAVE_IMMEDIATE = 'immediate';
        const SAVE_MANU = 'manual';

        protected static $_update = 'changed';
        protected static $_save = 'manual';

        protected $_mapping = array();
        protected $_changed = array();

        public function __construct()
        {
            $this->_mapping = call_user_func_array([$this, 'map'], func_get_args());
        }

        public static function mode($save = null, $update = self::UPDATE_CHANGE_VALUE)
        {
            if ($update === static::UPDATE_ALL_VALUE or $update === static::UPDATE_CHANGE_VALUE) {
                static::$_update = $update;
            } else {
                throw new Exception("No good value of update mode", 0);
            }

            if ($save !== null) {
                if ($save === static::SAVE_IMMEDIATE or $save === static::SAVE_MANU) {
                    static::$_save = $save;
                } else {
                    throw new Exception("No good value of save mode", 1);
                }
            }
        }

        public function update()
        {
            if (static::$_save === static::SAVE_IMMEDIATE) {
                return;
            }

            $map = $this->_mapping;
            $changed = $this->_changed;
            if (static::$_update === static::UPDATE_CHANGE_VALUE) {
                $this->_update(array_filter($this->_mapping, function ($var) use (&$map, &$changed) {
                    $key = key($map);
                    next($map);

                    if (in_array($key, $changed)) {
                        return $var;
                    }

                }));
            } else {
                $this->_update($this->_mapping);
            }
        }

        public function offsetExists($offset)
        {
            return array_key_exists($offset, $this->_mapping);
        }

        public function offsetGet($offset)
        {
            return $this->_mapping[$offset];
        }

        public function offsetSet($offset, $value)
        {
            if (!array_key_exists($offset, $this->_mapping)) {
                throw new Exception("Not allow to create column with the model attribute column (%s) not exists", 2, array($offset));
            }

            $this->_mapping[$offset] = $value;
            if (static::$_save === static::SAVE_IMMEDIATE) {
                $this->unmap($offset, $value);
            } elseif (!in_array($offset, $this->_changed)) {
                    $this->_changed[] = $offset;
            }

        }

        public function offsetUnset($offset)
        {
            $this->offsetSet($offset, null);
        }

        public function map()
        {
        }

        public function unmap($column, $value)
        {
        }

        public function _update(Array $array = array())
        {
        }

        public function dump()
        {
            return  '<pre> Model '.print_r($this->_mapping, true).'</pre>';
        }
    }
}
