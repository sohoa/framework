<?php

namespace Sohoa\Framework\Tests\Unit;

use Hoa\Dispatcher\Basic;
use Hoa\Router\Http;
use Sohoa\Framework\Kit as _Kit;

require_once __DIR__ . '/../Runner.php';

class myView implements \Hoa\View\Viewable {

    protected $_overlay = array();

    public function addOverlay($file) {

        $this->_overlay[] = $file;
    }

    public function getOverlay() {

        return $this->_overlay;
    }

    public function getOutputStream() {
    }

    public function getData() {
    }

    public function render() {
    }

    public function getRouter() {
    }
}

class Kit extends \atoum\test
{

    protected $envController = 'Foo';
    protected $envAction = 'Bar';
    protected $envFile = 'hoa://Application/View/Foo/Bar.xyl';

    protected function init() {

        $router = new Http();
        $router->get('t', '/', $this->envController, $this->envAction);

        $dispatcher = new Basic();
        $view       = new myView();
        $kit        = new _Kit($router, $dispatcher, $view);

        $router->route('/');


        $this->sizeof($router->getTheRule())
             ->isEqualTo(7)
             ->in($router->getTheRule())
             ->string[4]->isEqualTo($this->envController)
             ->string[5]->isEqualTo($this->envAction);

        return array(
            'kit'  => $kit,
            'view' => $view
        );
    }


    public function testRenderBasic() {

        $init = $this->init();
        $view = $init['view'];
        $kit  = $init['kit'];

        $this->string($kit->render())
             ->isIdenticalTO($this->envFile);

        $this->array($view->getOverlay())
             ->contains($this->envFile);
    }

    public function testRenderArray() {

        $init = $this->init();
        $view = $init['view'];
        $kit  = $init['kit'];

        $this->string($kit->render(array('Qux', 'Gordon')))
             ->isIdenticalTO('hoa://Application/View/Qux/Gordon.xyl')
             ->isNotIdenticalTo($this->envFile);


        $this->string($kit->render(array('controller' => 'Freeman', 'action' => 'Hawk')))
             ->isIdenticalTO('hoa://Application/View/Freeman/Hawk.xyl')
             ->isNotIdenticalTo($this->envFile);

        $this->array($view->getOverlay())->containsValues(
            array(
                'hoa://Application/View/Qux/Gordon.xyl',
                'hoa://Application/View/Freeman/Hawk.xyl'
            )
        )->notContains($this->envFile);
    }

    public function testRenderString() {

        $init = $this->init();
        $view = $init['view'];
        $kit  = $init['kit'];

        $this->string($kit->render('hoa://Application/View/Hello/World.xyl'))
             ->isIdenticalTO('hoa://Application/View/Hello/World.xyl')
             ->isNotIdenticalTo($this->envFile);

        $this->array($view->getOverlay())
             ->contains('hoa://Application/View/Hello/World.xyl')
             ->notContains($this->envFile);
    }
}
