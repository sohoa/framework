<?php
namespace Sohoa\Framework\Validator\Tests\Unit;

require_once __DIR__ . '/../../Runner.php';

class Length extends \atoum\test {
    public function testLength()
    {
        $length =  new \Sohoa\Framework\Validator\Length();
        $length->setName('waza');
        $length->setData('oula');
        $length->setArguments([4]);


        $this
            ->string($length->getName())->isIdenticalTo('waza')
            ->array($length->getArguments())->hasSize(1)

            ->boolean($length->isValid())->isTrue()
            ->variable($length->getMessage())->isNull()

            ->boolean($length->isValid(null , [5]))->isFalse()
            ->string($length->getMessage())->isIdenticalTo('The given value is not valid, need = 5 char given 4')

            ->boolean($length->isValid(null , [null, 4]))->isTrue()
            ->variable($length->getMessage())->isNull()

            ->boolean($length->isValid(null , [4, 15]))->isTrue()
            ->variable($length->getMessage())->isNull()

            ->boolean($length->isValid(null , [null, null]))->isTrue() // 0 to strlen('oula')
            ->variable($length->getMessage())->isNull()

            ->boolean($length->isValid(null , [2, null]))->isTrue() // 2 to strlen('oula')
            ->variable($length->getMessage())->isNull()

            ->boolean($length->isValid(null , [null, 3]))->isFalse() // 0 to 3
            ->string($length->getMessage())->isIdenticalTo('The given value is not valid, need >= 0 and <= 3 char given 4')
        ;



    }
}
