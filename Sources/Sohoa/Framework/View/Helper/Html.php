<?php

/**
 * Html helper
 *
 * @author osaris
 */

namespace Sohoa\Framework\View\Helper {

    use Sohoa\Framework\View;

    class Html extends View\Helper
    {
      public function __call( $name, $arguments ) {

        if(in_array($name, array('css', 'image', 'js'))) {

          $router = $this->view->getRouter();

          if($name === 'image')
            $name = 'images';

          return urldecode($router->unroute('_resource', array('resource' => $name . '/' . $arguments[0])));
        }
      }

      public function url( $route ) {

        return $this->view->getRouter()->unroute($route);
      }
    }
}
