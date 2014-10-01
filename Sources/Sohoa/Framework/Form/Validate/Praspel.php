<?php
namespace Sohoa\Framework\Form\Validate {

    class Praspel extends Validate
    {
        protected $_detail = 'The given value %s do not match %s';
        protected $realdom = 0;
        protected $value   = 0;

        protected function _valid($data, $argument)
        {

            $argument       = array_shift($argument);
            $praspel        = \Hoa\Praspel\Praspel::interprete('@requires i: '.$argument.';');
            $clause         = $praspel->getClause('requires');
            $variable       = $clause['i'];
            $this->realdom  = $argument;
            $this->value    = $data;

            return $variable->predicate($data);
        }

        protected function getDetail()
        {
            return sprintf($this->_detail, var_export($this->value, true), $this->realdom);
        }
    }
}
