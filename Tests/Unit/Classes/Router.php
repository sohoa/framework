<?php

namespace Sohoa\Framework\Tests\Unit;

require_once __DIR__ . '/../Runner.php';

class Router extends \atoum\test
{

    public function beforeTestMethod($testMethod)
    {

        $this->define->rule = '\Sohoa\Framework\Tests\Unit\Asserters\Rule';
    }

    public function testGetWithoutTo()
    {

        $router = new \Sohoa\Framework\Router;

        $this->exception(function () use ($router) {
            $router->get('/test', array('as' => 'test'));
        })
            ->hasMessage('Missing to !');
    }

    public function testGet()
    {

        $router = new \Sohoa\Framework\Router;
        $router->get('/test', array('as' => 'test', 'to' => 'Test#index'));

        $rule = $router->getRule('test');
        $this->rule($rule)
            ->methodIsEqualTo(array('get'))
            ->callIsEqualTo('Test')
            ->ableIsEqualTo('index');
    }

    public function testPost()
    {

        $router = new \Sohoa\Framework\Router;
        $router->post('/test', array('as' => 'test', 'to' => 'Test#test'));

        $rule = $router->getRule('test');
        $this->rule($rule)
            ->methodIsEqualTo(array('post'))
            ->callIsEqualTo('Test')
            ->ableIsEqualTo('test');
    }

    public function testPut()
    {

        $router = new \Sohoa\Framework\Router;
        $router->put('/test', array('as' => 'test', 'to' => 'Test#test'));

        $rule = $router->getRule('test');
        $this->rule($rule)
            ->methodIsEqualTo(array('put'))
            ->callIsEqualTo('Test')
            ->ableIsEqualTo('test');
    }

    public function testDelete()
    {

        $router = new \Sohoa\Framework\Router;
        $router->delete('/test', array('as' => 'test', 'to' => 'Test#test'));

        $rule = $router->getRule('test');
        $this->rule($rule)
            ->methodIsEqualTo(array('delete'))
            ->callIsEqualTo('Test')
            ->ableIsEqualTo('test');
    }

    public function testAny()
    {

        $router = new \Sohoa\Framework\Router;
        $router->any('/test', array('as' => 'test', 'to' => 'Test#test'));

        $rule = $router->getRule('test');
        $this->rule($rule)
            ->methodIsEqualTo(array('get', 'post', 'put', 'delete'))
            ->callIsEqualTo('Test')
            ->ableIsEqualTo('test');
    }

    public function testResource()
    {

        $router = new \Sohoa\Framework\Router;
        $router->resource('vehicles');

        $this->array($router->getRules())
            ->hasKeys(array('indexVehicles',
                'showVehicles',
                'createVehicles',
                'editVehicles',
                'updateVehicles',
                'destroyVehicles'));
    }

    public function testResourceWithOnly()
    {

        $router = new \Sohoa\Framework\Router;
        $router->resource('vehicles', array('only' => array('index', 'show')));

        $this->array($router->getRules())
            ->hasSize(2)
            ->notHasKeys(array('createVehicles',
                'editVehicles',
                'updateVehicles',
                'destroyVehicles'));
    }

    public function testResourceWithExcept()
    {

        $router = new \Sohoa\Framework\Router;
        $router->resource('vehicles', array('except' => array('destroy')));

        $this->array($router->getRules())
            ->hasSize(6)
            ->notHasKeys(array('destroyVehicles'));
    }

    public function testResourceWithOnlyAndExcept()
    {

        $router = new \Sohoa\Framework\Router;
        $router->resource('vehicles', array('only'   => array('index', 'show'),
                                            'except' => array('destroy')));

        $this->array($router->getRules())
            ->hasSize(2)
            ->notHasKeys(array('createVehicles',
                'editVehicles',
                'updateVehicles',
                'destroyVehicles'));
    }

    public function testNestedResource()
    {

        $router = new \Sohoa\Framework\Router();
        $router->resource('vehicles')->resource('fireman');

        $this->array($router->getRules())
            ->hasKeys(array(
                'indexVehicles',
                'showVehicles',
                'createVehicles',
                'editVehicles',
                'updateVehicles',
                'destroyVehicles',
                'indexFireman',
                'showFireman',
                'createFireman',
                'editFireman',
                'updateFireman',
                'destroyFireman'
            ));

    }

    public function testNestedResourceWithOnly()
    {

        $router = new \Sohoa\Framework\Router;
        $router->resource('vehicles', array('only' => array('index', 'show')))->resource('fireman', array('only' => array('index', 'show')));

        $this->array($router->getRules())
            ->hasSize(4)
            ->notHasKeys(array(
                'createVehicles',
                'editVehicles',
                'updateVehicles',
                'destroyVehicles',
                'createFireman',
                'editFireman',
                'updateFireman',
                'destroyFireman'
            ));
    }

    public function testNestedResourceWithExcept()
    {

        $router = new \Sohoa\Framework\Router;
        $router->resource('vehicles', array('except' => array('destroy')))
            ->resource('fireman', array('except' => array('destroy')));

        $this->array($router->getRules())
            ->hasSize(12)
            ->notHasKeys(array('destroyVehicles', 'destroyFireman'));
    }

    public function testNestedResourceWithOnlyAndExcept()
    {

        $router = new \Sohoa\Framework\Router;
        $router->resource('vehicles', array('only' => array('index', 'show'), 'except' => array('destroy')))
            ->resource('fireman', array('only' => array('index', 'show'), 'except' => array('destroy')));

        $this->array($router->getRules())
            ->hasSize(4)
            ->notHasKeys(array(
                'createVehicles',
                'editVehicles',
                'updateVehicles',
                'destroyVehicles',
                'createFireman',
                'editFireman',
                'updateFireman',
                'destroyFireman'
            ));
    }

    public function testModifyRessource()
    {
        $router = new \Sohoa\Framework\Router;
        $this->if($router->setRessource(\Sohoa\Framework\Router::REST_SHOW, null, 'post', null))
            ->then
            ->array($router->getRessource(\Sohoa\Framework\Router::REST_SHOW))
            ->hasSize(3)
            ->string[0]->isIdenticalTo('show')
            ->string[1]->isIdenticalTo('post')
            ->string[2];

        $this->if($router->setRessource(\Sohoa\Framework\Router::REST_SHOW, 'foo', 'post', null))
            ->then
            ->array($router->getRessource(\Sohoa\Framework\Router::REST_SHOW))
            ->hasSize(3)
            ->string[0]->isIdenticalTo('foo')
            ->string[1]->isIdenticalTo('post')
            ->string[2];

        $this->if($router->setRessource(\Sohoa\Framework\Router::REST_SHOW, 'foo', 'post', 'bar'))
            ->then
            ->array($router->getRessource(\Sohoa\Framework\Router::REST_SHOW))
            ->hasSize(3)
            ->string[0]->isIdenticalTo('foo')
            ->string[1]->isIdenticalTo('post')
            ->string[2]->isIdenticalTo('bar');

    }

    public function testGetResources()
    {

        $router = new \Sohoa\Framework\Router;

        $this->array($router->getRessources())
            ->hasSize(7);
    }

    public function testAddResource(){

        $router = new \Sohoa\Framework\Router;

        $this->integer($i = $router->addResourceRule('b' , 'head' , '/foo/'));
        $this->object($router->resource('foo'))
            ->isInstanceOf('\Sohoa\Framework\Router\Resource')
            ->then
            ->array($router->getRules())
                ->hasKeys(array(
                    'bFoo'
                ));

    }

    /**
     * Hoa\Xyl add private rule to the router beginning with "_" so Sohoa\Router
     * must be compatible with this behavior
     */

    public function testAddingAPrivateRule()
    {

        $router = new \Sohoa\Framework\Router;
        $router->_any('_resource', '/(?)/(?)');

        $rule = $router->getRule('_resource');
        $this->rule($rule)
            ->methodIsEqualTo(array('get'));
    }

    public function testAddGenericRule()
    {
        $this
            ->if($router = new \Sohoa\Framework\Router)
            ->and($router->any('(?<controller>)(?<action>)'))
            ->assert('Generic route has "generic" as id')
            ->boolean($router->ruleExists(\Sohoa\Framework\Router::ROUTE_GENERIC))
            ->isTrue();

    }
}
