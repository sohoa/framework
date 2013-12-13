<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 13/12/13
 * Time: 10:21
 */
namespace Sohoa\Framework {
    use Sohoa\Framework\Validate\Validatable;

    class Validate
    {
        private $_data = array();
        private $_valid = array();
        private $_error = array();

        public function __construct(Array $data, Array $validation)
        {
            $this->setData($data);
            $this->setValidation($validation);
        }

        public function setData($data)
        {
            $this->_data = $data;
        }

        public function setValidation($validation)
        {
            foreach ($validation as $name => $valid)
                foreach (explode('|', $valid) as $string)
                    if (preg_match('#([a-z]+)([=><]{1,2})(.*)#', $string, $match) === true)
                        $this->_valid[$name][] = $match;
        }

        public function getData($name)
        {
            if (array_key_exists($name, $this->_data))
                return $this->_data[$name];

            return '';
        }

        public function getValidationString($name)
        {
            if (array_key_exists($name, $this->_valid))
                return $this->_valid[$name];

            return array();
        }

        protected function _valid($function, $operation, $value, $data)
        {
            $classname = '\\Sohoa\\Framework\\Validate\\' . ucfirst($function);
            $instance  = dnew($classname, array($operation, $value));

            if ($instance instanceof Validatable) {
                $instance->setData($data);

                return $instance->isValid();
            }
            return false;
        }

        public function valid($name)
        {
            $data  = $this->getData($name);
            $valid = $this->getValidationString($name);
            $v     = true;

            foreach ($valid as $d)
                if ($this->_valid($d[1], $d[2], $d[3], $data) === false) {
                    $this->_error[$name][] = $d[0];
                    $v                     = false;
                }

            return $v;
        }

        public function hasError($name)
        {
            return array_key_exists($name, $this->_error);
        }

        public function getError($name)
        {
            if ($this->hasError($name) === true)
                return $this->_error[$name];

            return array();
        }

        public function getErrors()
        {
            return $this->_error;
        }

        public function isValid()
        {
            foreach ($this->_data as $name => $value)
                if ($this->valid($name) === false)
                    return false;

            return true;
        }
    }
}
 