<?php
namespace Sohoa\Framework\Validator\Tests\Unit;

require_once __DIR__ . '/../../Runner.php';

class Praspel extends \atoum\test {
    public function testPraspel()
    {
        $praspel =  new \Sohoa\Framework\Validator\Praspel();
        $praspel->setArguments(['boundinteger(0,5)']);

        $this
            ->boolean($praspel->isValid(1))->isTrue()
            ->variable($praspel->getMessage())->isNull()
            ->boolean($praspel->isValid(8))->isFalse()
            ->variable($praspel->getMessage())->isIdenticalTo('The given value 8 do not match boundinteger(0,5)');
        ;
    }
}
