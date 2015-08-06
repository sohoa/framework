<?php

namespace Sohoa\Framework\Model {

    class ActiveRecord
    {
        protected static $_log = array();
        protected $_defaultMappingLayer = 'default';
        protected $_result = array();

        public function getMappingLayer($name = null)
        {
            if ($name === null) {
                $name = $this->_defaultMappingLayer;
            }

            return \Hoa\Database\Dal::getInstance($name);
        }

        public function sql($sql, Array $data = array())
        {
            if (is_object($sql)) {
                $sql = strval($sql);
            }

            if (empty($data)) {
                $this->_result =  $this->getMappingLayer()->query($sql)->execute()->fetchAll();
            } else {
                $this->_result = $this->getMappingLayer()->prepare($sql)->execute($data)->fetchAll();
            }

            static::log($sql, count($this->_result));

            return $this;
        }

        public static function log($sql, $nb)
        {
            static::$_log[] = array($sql, $nb);
        }

        public static function getSqlLog()
        {
            return static::$_log;
        }

        public function first()
        {
            return $this->item(0);
        }

        public function last()
        {
            return $this->item(($this->count() - 1));
        }

        public function item($id)
        {
            if (array_key_exists($id, $this->_result)) {
                return $this->_result[$id];
            }

            return null;
        }

        public function all()
        {
            return  $this->_result;
        }

        public function count()
        {
            return count($this->all());
        }
    }
}
