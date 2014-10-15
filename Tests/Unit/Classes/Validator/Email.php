<?php
namespace Sohoa\Framework\Validator\Tests\Unit;

require_once __DIR__ . '/../../Runner.php';

class Email extends \atoum\test {
    public function testemail()
    {
        $email =  new \Sohoa\Framework\Validator\Email();

        $this
            ->boolean($email->isValid('foo+bar_a-5@hoa-project.net'))->isTrue()
            ->variable($email->getMessage())->isNull()
            ->boolean($email->isValid(8))->isFalse()
            ->variable($email->getMessage())->isIdenticalTo('The given value is not an valid email address')
            ->boolean($email->isValid('goo.gle.fr'))->isFalse()
            ->variable($email->getMessage())->isIdenticalTo('The given value is not an valid email address')
            ->boolean($email->isValid('goo@gle.fr'))->isTrue()
            ->variable($email->getMessage())->isNull()
        ;
    }
}
