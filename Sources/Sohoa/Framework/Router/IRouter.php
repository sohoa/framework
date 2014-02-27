<?php
/**
 * Created by PhpStorm.
 * User: Camael24
 * Date: 27/02/14
 * Time: 09:21
 */
namespace Sohoa\Framework\Router {
    use Sohoa\Framework\Framework;

    interface IRouter
    {

        public function construct();

        public function setFramework(Framework $framework);

        public function getFramework();

    }
}
