<?php

namespace Sohoa\Framework\Tests\Unit;

require_once __DIR__ . '/Runner.php';

class Framework extends \atoum\test
{

    public function testInit()
    {
        $fwk        = new \Sohoa\Framework\Framework();
        $router     = $fwk->router;
        $dispatcher = $fwk->dispatcher;

        $this->object($router)
            ->isInstanceOf('\Sohoa\Framework\Router');

        $this->object($dispatcher)
            ->isInstanceOf('\Hoa\Dispatcher\Basic');
    }

    public function testServices()
    {
        $fwk    = new \Sohoa\Framework\Framework();
        $router = \Sohoa\Framework\Framework::services('router');

        $this->object($router)
            ->isInstanceOf('\Sohoa\Framework\Router');
    }
}
