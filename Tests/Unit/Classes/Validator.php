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
            ->variable($o->getData())->isIdenticalTo('hello')
            ;

    }

}