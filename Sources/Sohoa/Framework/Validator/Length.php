<?php
namespace Sohoa\Framework\Validator  {
    class Length  extends Validator  {

        protected function _valid($data, $arguments)
        {
            $this->min = null;
            $this->max = null;
            $this->len = null;
            $this->s   = strlen($data);

            if(count($arguments) === 2) {
                $this->min = $arguments[0];
                $this->max = $arguments[1];

            }
            else if (count($arguments) === 1) {
                $this->len = $arguments[0];
            }
            else {
                throw new Exception("You need to use 1 or 2 arguments", 1);

            }

            if($this->len !== null){
                return strlen($data) === $this->len;
            }
            else {
                $this->max = ($this->max === null) ? strlen($data) : $this->max;
                $this->min = ($this->min === null) ? 0 : $this->min;

                return (strlen($data) >= $this->min && strlen($data) <= $this->max);
            }


        }

        protected function setMessage()
        {
            if($this->len !== null)
            {
                return sprintf('The given value is not valid, need = %s char given %s' , $this->len, $this->s);
            }
            else
            {
                return sprintf('The given value is not valid, need >= %s and <= %s char given %s' , $this->min , $this->max, $this->s);
            }
        }

    }
}