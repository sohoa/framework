<?php

namespace Sohoa\Tests\Unit;

require_once __DIR__ . '/runner.php';

class Router extends \atoum\test {

  public function testGet() {

    $router = new \Sohoa\Router;
    $router->get('/test', array('as' => 'test'));

    $this->array($router->getRule('test')[\Hoa\Router::RULE_METHODS])
         ->strictlyContainsValues(array('get'));
  }

  public function testPost() {

    $router = new \Sohoa\Router;
    $router->post('/test', array('as' => 'test'));

    $this->array($router->getRule('test')[\Hoa\Router::RULE_METHODS])
         ->strictlyContainsValues(array('post'));
  }

  public function testPut() {

    $router = new \Sohoa\Router;
    $router->put('/test', array('as' => 'test'));

    $this->array($router->getRule('test')[\Hoa\Router::RULE_METHODS])
         ->strictlyContainsValues(array('put'));
  }

  public function testDelete() {

    $router = new \Sohoa\Router;
    $router->delete('/test', array('as' => 'test'));

    $this->array($router->getRule('test')[\Hoa\Router::RULE_METHODS])
         ->strictlyContainsValues(array('delete'));
  }

  public function testAny() {

    $router = new \Sohoa\Router;
    $router->any('/test', array('as' => 'test'));

    $this->array($router->getRule('test')[\Hoa\Router::RULE_METHODS])
         ->strictlyContainsValues(array('get', 'post', 'put', 'delete'));
  }

  public function testResource() {

    $router = new \Sohoa\Router;
    $router->resource('vehicles');

    $this->array($router->getRules())
         ->hasKeys(array('indexVehicles',
                         'showVehicles',
                         'createVehicles',
                         'editVehicles',
                         'updateVehicles',
                         'destroyVehicles'));
  }
}