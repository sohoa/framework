<?php
namespace {

    require_once __DIR__ . '/../../Runner.php';

    class Foo extends \Sohoa\Framework\Model\Model
    {
        protected $_u = array();

        public function map($a)
        {
            return $a;
        }

        public function unmap($key, $value)
        {
            $this->_u[$key] = $value;
        }

        public function _update($a)
        {
            foreach ($a as $key => $value) {
                $this->unmap($key, $value);
            }
        }

        public function getU()
        {
            return $this->_u;
        }
    }
}

namespace Sohoa\Framework\Model\Tests\Unit {

    class Model extends \atoum\test
    {
        public function testHydrate()
        {
            $model = new \Foo(array('foo' => 'barssss', 'bar' => 2));

            $this
                ->object($model)
                ->string($model['foo'])->isEqualTo('barssss')
                ->integer($model['bar'])->isEqualTo(2);

        }

        public function testAutoUpdate()
        {
            \Foo::mode(\Foo::SAVE_IMMEDIATE);

            $model = new \Foo(array('foo' => 'barssss', 'bar' => 2));

            $this
                ->object($model)
                ->string($model['foo'])->isEqualTo('barssss')
                ->integer($model['bar'])->isEqualTo(2);

            $model['foo'] = 'hello';

            $this	->string($model['foo'])->isEqualTo('hello')
                    ->array($model->getU())->hasSize(1);

        }

        public function testManuUpdate()
        {
            \Foo::mode(\Foo::SAVE_MANU);
            $model = new \Foo(array('foo' => 'barssss', 'bar' => 2));

            $this
                ->object($model)
                ->string($model['foo'])->isEqualTo('barssss')
                ->integer($model['bar'])->isEqualTo(2);

            $model['foo'] = 'hello';

            $this	->string($model['foo'])->isEqualTo('hello')
                    ->array($model->getU())->hasSize(0);

            $model->update();
            $model['foo'] = 'qux';

            $this
                    ->array($u = $model->getU())->hasSize(1)
                    ->string($u['foo'])->isEqualTo('hello');

            $model->update();

            $this
                    ->array($u = $model->getU())->hasSize(1)
                    ->string($u['foo'])->isEqualTo('qux');

        }

        public function testManuAllUpdate()
        {
            \Foo::mode(\Foo::SAVE_MANU, \Foo::UPDATE_ALL_VALUE);
            $model = new \Foo(array('foo' => 'barssss', 'bar' => 2));

            $this
                ->object($model)
                ->string($model['foo'])->isEqualTo('barssss')
                ->integer($model['bar'])->isEqualTo(2);

            $model['foo'] = 'hello';

            $this	->string($model['foo'])->isEqualTo('hello')
                    ->array($model->getU())->hasSize(0);

            $model->update();
            $model['foo'] = 'qux';

            $this
                    ->array($u = $model->getU())->hasSize(2)
                    ->string($u['foo'])->isEqualTo('hello');

            $model->update();

            $this
                    ->array($u = $model->getU())->hasSize(2)
                    ->string($u['foo'])->isEqualTo('qux');
        }

        public function testAutoAllUpdate()
        {
            \Foo::mode(\Foo::SAVE_IMMEDIATE, \Foo::UPDATE_ALL_VALUE); // That no sense :)

            $model = new \Foo(array('foo' => 'barssss', 'bar' => 2));

            $this
                ->object($model)
                ->string($model['foo'])->isEqualTo('barssss')
                ->integer($model['bar'])->isEqualTo(2);

            $model['foo'] = 'hello';

            $this	->string($model['foo'])->isEqualTo('hello')
                    ->array($model->getU())->hasSize(1); // You send only the change value on immediate change, for no spamming db

            $model['bar'] = 'Hywan is PhD !!!!!!';
            $this	->string($model['bar'])->isEqualTo('Hywan is PhD !!!!!!')
                    ->array($model->getU())->hasSize(2); // We have update all :)

        }

        public function testInsert()
        {
             $model = new \Foo(array('foo' => 'barssss', 'bar' => 2));

             $this->exception(function () use ($model) {
                 $model['hello'] = 'world';
             });
        }
    }
}
