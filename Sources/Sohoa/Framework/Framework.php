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

        public $environnement = null;

        /**
         *
         * @var ErrorHandler
         */
        protected $errorHandler;

        /**
         *
         * On définie le router , le dispatcher, et la vue
         *
         * Router       : bootstrap.router.handler
         * Dispatcher   : bootstrap.dispatcher.handler
         * View         : est définie manuellement par l'utilisateur
         *
         */

        public function __construct($environnement = 'production')
        {

            $core       = Core::getInstance();
            $parameters = $core->getParameters();

            $parameters->setParameter('protocol.Application', '(:cwd:h:)/Application/');
            $parameters->setParameter('protocol.Public', '(:%root.application:)/Public/');
            $parameters->setParameter('namespace.prefix.Application', '(:cwd:h:)/');

            $core->setProtocol();


            $this->router        = new Router();
            self::services('router', $this->router);
            $this->dispatcher    = new Basic();
            $this->environnement = new Environnement($environnement);
            $this->setErrorHandler(new ErrorHandler());

            if (file_exists('hoa://Application/Config/Route.php')) {
                $framework = $this;
                require_once 'hoa://Application/Config/Route.php';
            }

        }

        public function run()
        {

            try {

                $this->dispatcher->dispatch($this->router, $this->view);
            } catch (\Exception $e) {

                $this->errorHandler->manageError($e);
                $this->dispatcher->dispatch($this->errorHandler->getRouter(), $this->view);
            }
        }

        public static function services($identifier, $object = null)
        {
            if (empty($identifier))
                throw new \Exception('Identifier can\'t be empty');

            if ($object === null)
                return Registry::get($identifier);

            Registry::set($identifier, $object);
        }

        /**
         *
         * @return \Sohoa\Framework\ErrorHandler
         */
        public function getErrorHandler()
        {
            return $this->errorHandler;
        }

        /**
         *
         * @param \Sohoa\Framework\ErrorHandler $errorHandler
         */
        public function setErrorHandler(ErrorHandler $errorHandler)
        {
            $this->errorHandler = $errorHandler;
            Framework::services('errorhandler', $this->errorHandler);
            $this->errorHandler->setRouter($this->router);
        }

    }

}
