<?php
namespace Sohoa\Framework\Validator\Tests\Unit;

require_once __DIR__ . '/../../Runner.php';

class Required extends \atoum\test {
    public function testRequired()
    {
        $required =  new \Sohoa\Framework\Validator\Required();
        $required->setName('waza');
        $required->setData('oula');

        $this
            ->boolean($required->isValid())->isTrue()
            ->boolean($required->isValid(null))->isTrue() // Use the default value
            ->boolean($required->isValid(''))->isFalse()
            ->boolean($required->isValid(11111))->isTrue()
            ;
    }
}
