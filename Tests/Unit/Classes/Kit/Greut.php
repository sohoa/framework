<?php

namespace Sohoa\Framework\Kit\Tests\Unit;

use Hoa\Dispatcher\Basic;
use Hoa\Router\Http;
use Hoa\Stringbuffer\ReadWrite;
use Sohoa\Framework\Framework;
use Sohoa\Framework\Kit as Kit;

require_once __DIR__ . '/../../Runner.php';

class Greut extends \atoum\test
{

    private $_router;
    private $_view;
    private $_kit;

    public function __construct()
    {

        parent::__construct();

        $this->_router = new Http();
        $this->_router->get('c', '/(?<_call>.[^/]+)/(?<_able>.*)', 'main', 'index');
        $this->_router->get('h', '/', 'main', 'index');

        $dispatcher  = new Basic();
        $this->_view = new \Sohoa\Framework\View\Greut();
        $kit         = new Kit($this->_router, $dispatcher, $this->_view);
        $this->_kit  = $kit->greut;
    }

    public function testReal()
    {

        $this->_view->setOutputStream(new ReadWrite());
        $this->_view->setPath(dirname(dirname(dirname(__FILE__))) . "/Template");
        $kit           = $this->_kit;
        $data          = $this->_view->getData();
        $data->title   = 'Title';
        $data->lang    = 'FR-fr';
        $data->foo     = 'Bar';
        $data->charset = 'utf-8';
        $kit->render('./index.tpl.php');

        $this->string($this->_view->getOutputStream()->readAll())->hasLengthGreaterThan(10);
    }

    /**
     * @dataProvider basicProvider
     */
    public function testBasic($rule, $controller, $action, $view)
    {
        $this->_router->route($rule);
        $this->sizeof($this->_router->getTheRule())
            ->isEqualTo(7)
            ->in($this->_router->getTheRule())
            ->string[4]->isEqualTo(strtolower($controller))
            ->string[5]->isEqualTo(strtolower($action));

        $v = $this->_kit;
        $this->exception(function () use ($v) {
             $v->render();
        })->message->contains($view);
    }

    /**
     * @dataProvider arrayProvider
     */
    public function testArray($array, $correctView)
    {
        $errorView = 'hoa://Application/View/Main/Index.xyl';
        $v         = $this->_kit;
        $this->exception(function () use ($v, $array) {
            $v->render($array);
        })->message->contains($correctView)->notContains($errorView);
    }

    /**
     * @dataProvider stringProvider
     */
    public function testString($filename)
    {

        $errorView = 'hoa://Application/View/Main/Index.xyl';
        $v         = $this->_kit;
        $this->exception(function () use ($v, $filename) {
            $v->render($filename);
        })->message->contains($filename)->notContains($errorView);;
    }

    public function basicProvider()
    {
        return array(
            array('/', 'Main', 'Index', 'hoa://Application/View/Main/Index.tpl.php'),
            array('/Foo/Bar', 'Foo', 'Bar', 'hoa://Application/View/Foo/Bar.tpl.php')
        );
    }

    public function arrayProvider()
    {
        return array(
            array(array('Qux', 'Gordon'), 'hoa://Application/View/Qux/Gordon.tpl.php'),
            array(array('controller' => 'Freeman', 'action' => 'Hawk'), 'hoa://Application/View/Freeman/Hawk.tpl.php')
        );
    }

    public function stringProvider()
    {
        return array(
            'hoa://Application/View/Foo/Bar.tpl.php',
            'hoa://Application/View/Qux/Gordon.tpl.php',
            'hoa://Application/View/Freeman/Hawk.tpl.php'
        );
    }
}