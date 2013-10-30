<?php

namespace Sohoa\Framework {


    use Hoa\Core\Core;
    use Hoa\Dispatcher\Basic;
    use Hoa\Registry\Registry;


    /**
     * Class Framework
     *
     * @package Sohoa\Framework
     */
    class Framework
    {
        /**
         * @var Router
         */
        public $router = null;

        /**
         * @var \Hoa\Dispatcher\Basic
         */
        public $dispatcher = null;

        /**
         * @var \Hoa\View\Viewable
         */
        public $view = null;

        /**
         *
         * On définie le router , le dispatcher, et la vue
         *
         * Router       : bootstrap.router.handler
         * Dispatcher   : bootstrap.dispatcher.handler
         * View         : est définie manuellement par l'utilisateur
         *
         */

        public function __construct()
        {

            try {
                $core             = Core::getInstance();
                $parameter        = $core->getParameters();
                $this->router     = new Router();
                $this->dispatcher = new Basic();

                self::services('router' , $this->router);

                if ($parameter instanceof Parameter) {

                    $parameter->setParameter('protocol.Application', '(:cwd:h:)/Application/');
                    $parameter->setParameter('protocol.Public', '(:%root.application:)/Public/');
                    $parameter->setParameter('namespace.prefix.Application', '(:cwd:h:)/');
                }
                $core->setProtocol();

                if (file_exists('hoa://Application/Config/Route.php')) {
                    require_once 'hoa://Application/Config/Route.php';
                }
            } catch (\Hoa\Core\Exception $e) {

                var_dump($e->getFormattedMessage());
            }
        }

        public function run()
        {

            try {

                $this->dispatcher->dispatch($this->router, $this->view);
            } catch (\Hoa\Core\Exception $e) {

                var_dump($e->getFormattedMessage());
            }
        }

        public static function services($identifier, $object = null)
        {
            if (empty($identifier))
                throw new \Exception('Identifier cant be empty');


            if ($object === null)
                return Registry::get($identifier);

            Registry::set($identifier, $object);
        }
    }
}
