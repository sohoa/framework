<?php
namespace Sohoa\Framework\Validator\Tests\Unit;

require_once __DIR__ . '/../../Runner.php';

class Required extends \atoum\test {
    public function testRequired()
    {
        $required =  new \Sohoa\Framework\Validator\Required();
        $required->setName('waza');
        $required->setData('oula');
        $required->setArguments([]);
        $this
            ->string($required->getName())->isIdenticalTo('waza')
            ->array($required->getArguments())->hasSize(0)
            ->boolean($required->isValid())->isTrue()
            ->boolean($required->isValid(null))->isTrue() // Use the default value
            ->boolean($required->isValid(''))->isFalse()
            ->boolean($required->isValid(11111))->isTrue()
            ;
    }

    public function testErrorsMessage()
    {
        $required =  new \Sohoa\Framework\Validator\Required();
        $required->setName('waza');
        $required->setData('oula');


        $this
            ->boolean($required->isValid())->isTrue()
            ->variable($required->getMessage())->isNull()

            ->boolean($required->isValid(''))->isFalse()
            ->variable($required->getMessage())->isIdenticalTo('This field is required');
    }
}
