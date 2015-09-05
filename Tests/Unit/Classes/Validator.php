<?php
namespace Sohoa\Framework\Tests\Unit;

require_once __DIR__ . '/../Runner.php';

class Validator extends \atoum\test
{
    public function testInitValidator() {
        $validator = \Sohoa\Framework\Validator::get('foo');

        $this
            ->object($validator)->isInstanceOf('\Sohoa\Framework\Validator')
            ->object($validator->validator)->isInstanceOf('\Sohoa\Framework\Validator')
            ->object($validator->filter)->isInstanceOf('\Sohoa\Framework\Validator')
            ->object($validator->foo)->isInstanceOf('\Sohoa\Framework\Validator');

        $validator
            ->foo
                ->validator->required()
                ->validator->length(5);

        $this->variable($validator->getStack('bar'))->isNull();
        $this
            ->array($validator->getStack('foo'))
            ->object[0]['object']->isInstanceOf('\Sohoa\Framework\Validator\Required')
            ->object[1]['object']->isInstanceOf('\Sohoa\Framework\Validator\Length')
            ->if($o = $validator->getStack('foo')[1]['object'])
            ->string($o->getName())
                ->isIdenticalTo('foo')
            ->variable($o->getData())
                ->isNull()
            ->array($o->getArguments())
                ->hasSize(1)
                ->integer[0]->isIdenticalTo(5)
            ;

        $validator->setData(['foo' => 'hello']);
        $validator
            ->foo
                ->validator->required()
                ->validator->length(5);

        $this
            ->if($o = $validator->getStack('foo')[3]['object'])
            ->object($o)->isInstanceOf('\Sohoa\Framework\Validator\Length')
            ->string($o->getData())->isIdenticalTo('hello')
            ;
    }

    public function testChainValidator()
    {
        $validator = \Sohoa\Framework\Validator::get('foo');

        $validator
            ->foo
                ->validator->required()
                ->validator->length(5);

        $this
            ->boolean($validator->isValid(['foo' => 'h']))->isFalse()
            ->array($validator->getErrors())
                ->hasSize(1)
                ->hasKey('foo')
            ->string['foo'][0]['class']->isIdenticalTo('Sohoa\Framework\Validator\Length')
            ->string['foo'][0]['message']->isIdenticalTo('The given value is not valid, need = 5 char given 1')
        ;

        $this
            ->boolean($validator->isValid(['foo' => 'hello']))->isTrue()
            ->array($validator->getErrors())->hasSize(0)
        ;

        $this
            ->boolean($validator->isValid(['foo' => null]))->isFalse()
            ->array($validator->getErrors())->hasSize(1)->hasKey('foo')
            ->array($validator->getError('foo'))->hasSize(2)
        ;
    }

    public function testComplexValidator()
    {
        $validator = \Sohoa\Framework\Validator::get('foo');

        $validator
            ->foo->length(0, 5);


        $this
            ->boolean($validator->isValid(['foo' => 'he']))->isTrue()
            ->array($validator->getErrors())->hasSize(0)
        ;

        $this->boolean($validator->isValid(['foo' => null]))->isTrue();

        $this
            ->boolean($validator->isValid(['foo' => 'alibaba']))->isFalse()
            ->array($validator->getErrors())->hasSize(1)->hasKey('foo')
            ->array($validator->getError('foo'))->hasSize(1)
        ;

    }

    public function testMultiValue()
    {
        $validator = \Sohoa\Framework\Validator::get('foo');

        $validator
            ->foo->length(0, 5)
            ->bar->required();


        $this
            ->boolean($validator->isValid(['foo' => 'he', 'bar' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa']))->isTrue()
            ->array($validator->getErrors())->hasSize(0)
        ;

    }

}