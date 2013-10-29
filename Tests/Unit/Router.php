<?php

namespace Sohoa\Framework\Tests\Unit;

require_once __DIR__ . '/Runner.php';

class Router extends \atoum\test {

    public function testGetWithoutTo() {

        $router = new \Sohoa\Framework\Router;

        $this->exception(function() use($router) {
            $router->get('/test', array('as' => 'test'));
        })
             ->hasMessage('Missing to !');
    }

    public function testGet() {

        $router = new \Sohoa\Framework\Router;
        $router->get('/test', array('as' => 'test', 'to' => 'Test#index'));

        $rule = $router->getRule('test');
        $this->array($rule[\Hoa\Router::RULE_METHODS])
             ->strictlyContainsValues(array('get'));
        $this->string($rule[\Hoa\Router::RULE_CALL])
             ->isEqualTo('Test');
        $this->string($rule[\Hoa\Router::RULE_ABLE])
             ->isEqualTo('index');
    }

    public function testPost() {

        $router = new \Sohoa\Framework\Router;
        $router->post('/test', array('as' => 'test', 'to' => 'Test#test'));

        $rule = $router->getRule('test');
        $this->array($rule[\Hoa\Router::RULE_METHODS])
             ->strictlyContainsValues(array('post'));
        $this->string($rule[\Hoa\Router::RULE_CALL])
             ->isEqualTo('Test');
        $this->string($rule[\Hoa\Router::RULE_ABLE])
             ->isEqualTo('test');
    }

    public function testPut() {

        $router = new \Sohoa\Framework\Router;
        $router->put('/test', array('as' => 'test', 'to' => 'Test#test'));

        $rule = $router->getRule('test');
        $this->array($rule[\Hoa\Router::RULE_METHODS])
             ->strictlyContainsValues(array('put'));
        $this->string($rule[\Hoa\Router::RULE_CALL])
             ->isEqualTo('Test');
        $this->string($rule[\Hoa\Router::RULE_ABLE])
             ->isEqualTo('test');
    }

    public function testDelete() {

        $router = new \Sohoa\Framework\Router;
        $router->delete('/test', array('as' => 'test', 'to' => 'Test#test'));

        $rule = $router->getRule('test');
        $this->array($rule[\Hoa\Router::RULE_METHODS])
             ->strictlyContainsValues(array('delete'));
        $this->string($rule[\Hoa\Router::RULE_CALL])
             ->isEqualTo('Test');
        $this->string($rule[\Hoa\Router::RULE_ABLE])
             ->isEqualTo('test');
    }

    public function testAny() {

        $router = new \Sohoa\Framework\Router;
        $router->any('/test', array('as' => 'test', 'to' => 'Test#test'));

        $rule = $router->getRule('test');
        $this->array($rule[\Hoa\Router::RULE_METHODS])
             ->strictlyContainsValues(array('get', 'post', 'put', 'delete'));
        $this->string($rule[\Hoa\Router::RULE_CALL])
             ->isEqualTo('Test');
        $this->string($rule[\Hoa\Router::RULE_ABLE])
             ->isEqualTo('test');
    }

    public function testResource() {

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

    public function testResourceWithOnly() {

        $router = new \Sohoa\Framework\Router;
        $router->resource('vehicles', array('only' => array('index', 'show')));

        $this->array($router->getRules())
             ->hasSize(2)
             ->notHasKeys(array('createVehicles',
                                'editVehicles',
                                'updateVehicles',
                                'destroyVehicles'));
    }

    public function testResourceWithExcept() {

        $router = new \Sohoa\Framework\Router;
        $router->resource('vehicles', array('except' => array('destroy')));

        $this->array($router->getRules())
             ->hasSize(6)
             ->notHasKeys(array('destroyVehicles'));
    }

    public function testResourceWithOnlyAndExcept() {

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
}