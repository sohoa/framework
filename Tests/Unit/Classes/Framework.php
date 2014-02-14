<?php

namespace Sohoa\Framework\Tests\Unit;

require_once __DIR__ . '/../Runner.php';

class Framework extends \atoum\test
{

    public function testInit()
    {

        $fwk        = new \Sohoa\Framework\Framework();
        $router     = $fwk->getRouter();
        $dispatcher = $fwk->getDispatcher();
        $view       = $fwk->getView();

        $this->object($router)
            ->isInstanceOf('\Sohoa\Framework\Router');

        $this->object($dispatcher)
            ->isInstanceOf('\Sohoa\Framework\Dispatcher\Basic');

        $this->object($view)
            ->isInstanceOf('\Sohoa\Framework\View\Greut');
    }

}
