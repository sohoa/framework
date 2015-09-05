<?php
namespace Sohoa\Framework\Validator\Tests\Unit;

require_once __DIR__ . '/../../Runner.php';

class Range extends \atoum\test {
    public function testrange()
    {
        $range =  new \Sohoa\Framework\Validator\Range();
        $range->setArguments([['a', 'b', 'c' , 'd' => 'e']]);

        $this
            ->boolean($range->isValid('a'))->isTrue()
            ->variable($range->getMessage())->isNull()

            ->boolean($range->isValid('e'))->isTrue()
            ->variable($range->getMessage())->isNull()

            ->boolean($range->isValid('d'))->isFalse()
            ->variable($range->getMessage())->isIdenticalTo('This field are not in authorized option')

            ->boolean($range->isValid('f'))->isFalse()
            ->variable($range->getMessage())->isIdenticalTo('This field are not in authorized option');
        ;
    }
}
