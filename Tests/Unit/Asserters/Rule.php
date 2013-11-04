<?php

namespace Sohoa\Framework\Tests\Unit\Asserters;

class rule extends \atoum\asserters\phpArray {

   public function __get($property) {

        switch ($property) {
            case 'toString':
                return $this->toString();

            default:
                return parent::__get($property);
        }
    }

    public function setWith($value, $checkType = true) {

        parent::setWith($value);

        if ($checkType === true) {

            if (self::isArray($this->value) === false) {

                $this->fail(sprintf($this->getLocale()->_('%s is not an array'), $this));
            }
            else {

                $this->pass();
            }
        }

        return $this;
    }

    public function methodIsEqualTo($methods) {

        $this->array($this->value[\Hoa\Router::RULE_METHODS])
             ->strictlyContainsValues($methods, sprintf(''));

        return $this;
    }

    public function callIsEqualTo($value) {

        $call = $this->value[\Hoa\Router::RULE_CALL];

        $this->string($call)
             ->isEqualTo($value, sprintf('Wrong call, %s expected but got %s', $value, $call));

        return $this;
    }

    public function ableIsEqualTo($value) {

        $able = $this->value[\Hoa\Router::RULE_ABLE];

        $this->string($able)
             ->isEqualTo($value, sprintf('Wrong able, %s expected but got %s', $value, $able));

        return $this;
    }
}
