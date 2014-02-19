<?php
/**
 * Created by PhpStorm.
 * User: Camael24
 * Date: 07/02/14
 * Time: 11:21
 */
namespace Sohoa\Framework\View {
    use Hoa\Router\Router;
    use Sohoa\Framework\Framework;

    interface Soview
    {
        public function setRouter(Router $router);

        public function setFramework(Framework $framework);
    }
}
