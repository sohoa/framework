<?php

namespace Sohoa\Framework\Kit\Tests\Unit;

use Hoa\Dispatcher\Basic;
use Hoa\Router\Http;
use Sohoa\Framework\Kit as Kit;

require_once __DIR__ . '/../../Runner.php';

class myView implements \Hoa\View\Viewable
{

    protected $_overlay = array();

    public function addOverlay($file)
    {

        $this->_overlay[] = $file;
    }

    public function getOverlay()
    {

        return $this->_overlay;
    }

    public function getOutputStream()
    {
    }

    public function getData()
    {
    }

    public function render()
    {
    }

    public function getRouter()
    {
    }
}

class Xyl extends \atoum\test
{

    private $_router;
    private $_view;
    private $_kit;

    public function __construct()
    {

        parent::__construct();

        Kit::add('xyl', new Kit\Xyl());

        $this->_router = new Http();
        $this->_router->get('c', '/(?<_call>.[^/]+)/(?<_able>.*)', 'Main', 'Index');
        $this->_router->get('h', '/', 'Main', 'Index');

        $dispatcher  = new Basic();
        $this->_view = new myView();
        $kit         = new Kit($this->_router, $dispatcher, $this->_view);
        $this->_kit  = $kit->xyl;
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
            ->string[4]->isEqualTo($controller)
            ->string[5]->isEqualTo($action);

        $this->string($this->_kit->render())
            ->isIdenticalTO($view);

        $this->array($this->_view->getOverlay())
            ->contains($view);

       $this->dump($this->_router->getTheRule());
    }

    /**
     * @dataProvider arrayProvider
     */
    public function testArray($array, $correctView)
    {
        $errorView = 'hoa://Application/View/Main/Index.xyl';

        $this->string($this->_kit->render($array))
            ->isIdenticalTO($correctView)
            ->isNotIdenticalTo($errorView);


        $this->array($this->_view->getOverlay())->contains($correctView)->notContains($errorView);
    }

    /**
     * @dataProvider stringProvider
     */
    public function testString($filename)
    {

        $errorView = 'hoa://Application/View/Main/Index.xyl';
        $this->string($this->_kit->render($filename))
            ->isIdenticalTO($filename)
            ->isNotIdenticalTo($errorView);

        $this->array($this->_view->getOverlay())
            ->contains($filename)
            ->notContains($errorView);
    }

    public function basicProvider()
    {
        return array(
            array('/', 'Main', 'Index', 'hoa://Application/View/Main/Index.xyl'),
            array('/Foo/Bar', 'foo', 'bar', 'hoa://Application/View/foo/bar.xyl')
        );
    }

    public function arrayProvider()
    {
        return array(
            array(array('Qux', 'Gordon'), 'hoa://Application/View/Qux/Gordon.xyl'),
            array(array('controller' => 'Freeman', 'action' => 'Hawk'), 'hoa://Application/View/Freeman/Hawk.xyl')
        );
    }

    public function stringProvider()
    {
        return array(
            'hoa://Application/View/foo/bar.xyl',
            'hoa://Application/View/Qux/Gordon.xyl',
            'hoa://Application/View/Freeman/Hawk.xyl'
        );
    }
}