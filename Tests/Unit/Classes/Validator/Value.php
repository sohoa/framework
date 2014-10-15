<?php
namespace Sohoa\Framework\Validator\Tests\Unit;

require_once __DIR__ . '/../../Runner.php';

class Value extends \atoum\test {
    public function testMin()
    {
        $value =  new \Sohoa\Framework\Validator\Value();
        $value->setArguments([5 , null]);

        $this
            ->boolean($value->isValid(8))->isTrue()
            ->variable($value->getMessage())->isNull()

            ->boolean($value->isValid(5))->isTrue()
            ->variable($value->getMessage())->isNull()

            ->boolean($value->isValid(4))->isFalse()
            ->variable($value->getMessage())->isIdenticalTo('The given value is not valid, need >= 5 and <= 2147483647 integer given 4')

            ->boolean($value->isValid(-100))->isFalse()
            ->variable($value->getMessage())->isIdenticalTo('The given value is not valid, need >= 5 and <= 2147483647 integer given -100')

            ->boolean($value->isValid('string'))->isFalse()
            ->variable($value->getMessage())
        ;
    }

    public function testMax()
    {
        $value =  new \Sohoa\Framework\Validator\Value();
        $value->setArguments([null , 5]);

        $this
            ->boolean($value->isValid(2))->isTrue()
            ->variable($value->getMessage())->isNull()

            ->boolean($value->isValid(-2000))->isTrue()
            ->variable($value->getMessage())->isNull()

            ->boolean($value->isValid(100))->isFalse()
            ->variable($value->getMessage())->isIdenticalTo('The given value is not valid, need >= -2147483647 and <= 5 integer given 100')

            ->boolean($value->isValid('string'))->isTrue() // String cast to int
            ->variable($value->getMessage())->isNull();
        ;
    }

    public function testBetween()
    {
        $value =  new \Sohoa\Framework\Validator\Value();
        $value->setArguments([3 , 5]);

        $this
            ->boolean($value->isValid(4))->isTrue()
            ->variable($value->getMessage())->isNull()

            ->boolean($value->isValid(3))->isTrue()
            ->variable($value->getMessage())->isNull()

            ->boolean($value->isValid(6))->isFalse()
            ->variable($value->getMessage())->isIdenticalTo('The given value is not valid, need >= 3 and <= 5 integer given 6')

            ->boolean($value->isValid('string'))->isFalse() // String cast to int
            ->variable($value->getMessage())->isIdenticalTo('The given value is not valid, need >= 3 and <= 5 integer given 0');
    }
}
