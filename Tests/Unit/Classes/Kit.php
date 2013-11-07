<?php

namespace Sohoa\Framework\Tests\Unit {

    use Hoa\Dispatcher\Basic;
    use Hoa\Router\Http;
    use Sohoa\Framework\Kit as _Kit;

    require_once __DIR__ . '/../Runner.php';


    class Xyl extends _Kit\Kitable
    {

    }

    class Kit extends \atoum\test
    {
        public function testAdd()
        {
            _Kit::add('xyl', new Xyl());
            _Kit::add('xyls', new Xyl());
            _Kit::add('xylsx', new Xyl());

            $kit = new _Kit(new Http(), new Basic());

            $this->sizeof($kit->getAllKits())->isEqualto(3);

            $this->object($kit->kit('xyl'))->isInstanceOf('\Sohoa\Framework\Tests\Unit\Xyl');

            $kit = $kit->kit('xyl');

            $this->object($kit->getRouter())->isInstanceOf('\Hoa\Router\Http');
            $this->variable($kit->getView())->isNull();

        }
    }
}