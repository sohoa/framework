<?php
namespace {
    use Sohoa\Framework\Kit;

    require_once __DIR__ . '/../Runner.php';

    class Xyl extends Kit\Kitable
    {

    }

    class Xylophone extends Kit\Kitable
    {

    }

}

namespace Application\Controller {
    use Sohoa\Framework\Kit;

    class Foo extends Kit
    {


        public function IndexAction()
        {

            return $this->foo;
        }
    }
}
namespace Sohoa\Framework\Tests\Unit {

    use Application\Controller\Foo;
    use Sohoa\Framework\Framework as Fwk;
    use Sohoa\Framework\Kit as _Kit;

    class Kit extends \atoum\test
    {
        protected $fwk = null;
        protected $kit = null;

        public function __construct()
        {
            parent::__construct();


            $this->fwk = new Fwk();
            $this->fwk->kit('foo', new \Xyl());
            $this->fwk->kit('bar', new \Xyl());
            $this->fwk->kit('wux', new \Xyl());

            $this->kit = new _Kit($this->fwk->getRouter(), $this->fwk->getDispatcher(), $this->fwk->getView(), $this->fwk);
        }

        public function testAdd()
        {


            $this->sizeof($this->fwk->getKits())->isEqualto(4);

            $this->object($this->fwk->kit('foo'))->isInstanceOf('\Xyl');

            $kit = $this->fwk->kit('foo');

            $this->object($kit->getRouter())->isInstanceOf('\Sohoa\Framework\Router');
            $this->object($kit->getView())->isInstanceOf('\Sohoa\Framework\View\Greut');
        }

        public function testLimitless()
        {

            $this->object($this->fwk->kit('foo'))->isInstanceOf('\Xyl');
            $this->fwk->kit('foo', new \Xylophone());
            $this->sizeof($this->fwk->getKits())->isEqualto(4);
            $this->object($this->fwk->kit('foo'))->isInstanceOf('\Xylophone');


        }

        public function testKitInController()
        {


            $controller = new Foo($this->fwk->getRouter(), $this->fwk->getDispatcher(), $this->fwk->getView(), $this->fwk);
            $xyl        = $controller->IndexAction();

            $this->object($xyl)->isInstanceOf('\Xyl');
        }

        public function testKitGet()
        {
            $this
                ->if($miscKit = new _Kit\Kitable)
                ->and($this->fwk->kit('foo', $miscKit))
                ->and($controller = new Foo($this->fwk->getRouter(), $this->fwk->getDispatcher(), $this->fwk->getView(), $this->fwk))
                ->assert('Kit property is a Kitable')
                ->object($controller->foo)->isIdenticalTo($miscKit);
        }


    }
}