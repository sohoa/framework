<?php
namespace Sohoa\Framework\Form\Validate {

    class Length extends Validate
    {
        protected $_detail = 'The given value is too long, need = %s char';
        protected $length  = 0;
        protected $max     = 0;
        protected $min     = 0;
        protected $ex      = array();

        protected function _valid($data, $argument)
        {
            $this->_form[$this->_currentName]->setAttribute('type' , 'numeric');

            if (in_array('getOptions', get_class_methods($this->_parent))) {
                throw new Exception("You cant set Length validator on item %s", 0, array(get_class($this->_parent)));
            }

            if (count($argument) == 1) {
                $this->length = array_shift($argument);
                $this->ex    = [$this->length];

                return (strlen($data) == intval($this->length));

            } else {
                $this->min    = array_shift($argument);
                $this->max    = array_shift($argument);

                if ($this->min === '' && $this->max === '') {
                    throw new Exception("Error syntax min and max value are empty", 1);
                }

                if ($this->min === '') {
                    $this->_detail = 'The given value is too long, need <= %s char';
                    $this->ex     = [$this->max];

                    return (strlen($data) <= $this->max);
                }

                if ($this->max === '') {
                    $this->_detail = 'The given value is too long, need >= %s char';
                    $this->ex     = [$this->min];

                    return (strlen($data) >= $this->min);
                }

                    $this->_detail = 'The given value is too long, need >= %s and <= %s char';
                    $this->ex     = [$this->min, $this->max];

                return (strlen($data) >= $this->min && strlen($data) <= $this->max);
            }

        }

        protected function getDetail()
        {
            $args = [
                'length' => $this->length,
                'max'    => $this->max,
                'min'    => $this->min
            ];

            return [
                'object'  => get_class($this),
                'message' => vsprintf($this->_detail, $this->ex),
                'args'    => $args
            ];
        }
    }
}
