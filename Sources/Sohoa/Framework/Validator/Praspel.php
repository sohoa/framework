<?php
namespace Sohoa\Framework\Validator  {
    class Praspel extends Validator {

        protected function _valid($data, $arguments)
        {
            if(count($arguments) and isset($arguments[0])){
                $argument       = $arguments[0];
                $praspel        = \Hoa\Praspel\Praspel::interprete('@requires i: '.$argument.';');
                $clause         = $praspel->getClause('requires');
                $variable       = $clause['i'];
                $this->realdom  = $argument;
                $this->value    = $data;

                return $variable->predicate($data);
            }

            throw new Exception("Need only one argument", 1);

        }

        protected function setMessage()
        {
            return  sprintf('The given value %s do not match %s', var_export($this->value, true), $this->realdom);
        }

    }
}