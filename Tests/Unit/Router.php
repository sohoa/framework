<?php

namespace Sohoa\Framework\Tests\Unit;

require_once __DIR__ . '/Runner.php';

class Router extends \atoum\test {

  public function testGet() {

    $router = new \Sohoa\Framework\Router;
    $router->get('/test', array('as' => 'test'));

    $rule = $router->getRule('test');
    $this->array($rule[\Hoa\Router::RULE_METHODS])
         ->strictlyContainsValues(array('get'));
  }

  public function testPost() {

    $router = new \Sohoa\Framework\Router;
    $router->post('/test', array('as' => 'test'));

    $rule = $router->getRule('test');
    $this->array($rule[\Hoa\Router::RULE_METHODS])
         ->strictlyContainsValues(array('post'));
  }

  public function testPut() {

    $router = new \Sohoa\Framework\Router;
    $router->put('/test', array('as' => 'test'));

    $rule = $router->getRule('test');
    $this->array($rule[\Hoa\Router::RULE_METHODS])
         ->strictlyContainsValues(array('put'));
  }

  public function testDelete() {

    $router = new \Sohoa\Framework\Router;
    $router->delete('/test', array('as' => 'test'));

    $rule = $router->getRule('test');
    $this->array($rule[\Hoa\Router::RULE_METHODS])
         ->strictlyContainsValues(array('delete'));
  }

  public function testAny() {

    $router = new \Sohoa\Framework\Router;
    $router->any('/test', array('as' => 'test'));

    $rule = $router->getRule('test');
    $this->array($rule[\Hoa\Router::RULE_METHODS])
         ->strictlyContainsValues(array('get', 'post', 'put', 'delete'));
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