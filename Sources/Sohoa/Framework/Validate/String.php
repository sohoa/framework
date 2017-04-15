<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 13/12/13
 * Time: 10:38
 */
namespace Sohoa\Framework\Validate {
    class String implements Validatable
    {
        protected $_function = null;
        protected $_operateur = null;
        protected $_value = null;
        protected $_data = null;
        protected $_operateurValid = array(
            '>'  => 'more',
            '>=' => 'morethan',
            '<'  => 'less',
            '<=' => 'lessthan',
            '='  => 'equal',
            '==' => 'equalstrict'
        );

        public function __construct($operateur, $value)
        {
            $this->setOperateur($operateur);
            $this->_value = $value;
        }

        public function setOperateur($operateur)
        {
            if (array_key_exists($operateur, $this->_operateurValid))
                $this->_operateur = $this->_operateurValid[$operateur];
        }

        public function isValid()
        {
            $data     = $this->_data;
            $value    = $this->_value;
            $operator = $this->_operateur;
            $return   = call_user_func_array($this->_function, array($data));

            switch ($operator) {
                case 'less':
                    return ($return < $value);
                    break;
                case 'lessthan':
                    return ($return <= $value);
                    break;
                case 'more':
                    return ($return > $value);
                    break;
                case 'morethan':
                    return ($return >= $value);
                    break;
                case 'equal':
                    return ($return == $value);
                    break;
                case 'equalstrict':
                    return ($return === $value);
                    break;
                default:
                    return false;
            }
        }

        public function setData($data)
        {
            $this->_data = $data;
        }
    }
}
 