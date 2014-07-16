<?php

namespace Sohoa\Framework {

    use Hoa\Core\Core;
    use Sohoa\Framework\Dispatcher\Basic;
    use Sohoa\Framework\Kit\Kitable;
    use Sohoa\Framework\Router\IRouter;
    use Sohoa\Framework\Session\Session;
    use Sohoa\Framework\View\Greut;
    use Sohoa\Framework\View\Soview;

    class Framework
    {

        /**
         * @var \Sohoa\Framework\Router
         */
        protected $_router = null;

        /**
         * @var \Sohoa\Framework\Dispatcher
         */
        protected $_dispatcher = null;

        /**
         * @var \Hoa\View\Viewable
         */
        protected $_view = null;

        /**
         * @var \Sohoa\Framework\Environnement
         */
        protected $_environnement = null;

        /**
         * @var \Sohoa\Framework\ErrorHandler
         */
        protected $_errorHandler = null;

        /**
         * @var \Sohoa\Framework\Session\Session
         */
        protected $_session      = null;

        /**
         * @var array
         */
        protected $_kit = array();

        /**
         * @var bool
         */
        protected static $_initialize = false;

        public static function initialize($cwd = null)
        {
            if (static::$_initialize === true)
                return;

            static::$_initialize = true;
            /**
             * @var \Hoa\Core\Parameter\Parameter $parameters
             * @var \Hoa\Core\Core $core
             */
            $core       = Core::getInstance();
            $parameters = $core->getParameters();

            if($cwd !== null)
                $parameters->setKeyword('cwd' , $cwd);

            $parameters->setParameter('protocol.Application', '(:cwd:h:)/Application/');
            $parameters->setParameter('protocol.Public', '(:%root.application:)/Public/');
            $parameters->setParameter('namespace.prefix.Application', '(:cwd:h:)/');

            $core->setProtocol();
        }

        /**
         * @param string $environnement
         */
        public function __construct($environnement = 'production')
        {

            static::initialize();

            $this->setEnvironnement($environnement);

            $this->construct();
        }

        public function construct()
        {

        }

        /**
         * @param IRouter $router
         */
        public function setRouter(IRouter $router)
        {
            $this->_router = $router;

            return $this;
        }

        /**
         * @param Dispatcher $dispatcher
         */
        public function setDispatcher(Dispatcher $dispatcher)
        {
            $this->_dispatcher = $dispatcher;

            return $this;
        }

        /**
         * @param Soview $view
         */
        public function setView(Soview $view)
        {
            $this->_view = $view;

            return $this;
        }

        public function setEnvironnement($useEnvironnement = null, Environnement $environnement = null)
        {
            $this->_environnement = $environnement ? : new Environnement($this, $useEnvironnement);

            return $this;
        }

        public function setSession(Session $session)
        {
            $this->_session = $session;

            return $this;
        }

        /**
         *
         * @param \Sohoa\Framework\ErrorHandler $errorHandler
         */
        public function setErrorHandler(ErrorHandler $errorHandler)
        {
            $this->_errorHandler = $errorHandler;

            return $this;
        }

        /**
         * @return \Hoa\Dispatcher\Basic
         */
        public function getDispatcher()
        {
            if ( !$this->_dispatcher) $this->initDispatcher();
            return $this->_dispatcher;
        }

        /**
         * @return \Sohoa\Framework\Router
         */
        public function getRouter()
        {
            if ( !$this->_router) $this->initRouter();
            return $this->_router;
        }

        /**
         * @return \Sohoa\Framework\View\Greut
         */
        public function getView()
        {
            if ( !$this->_view) $this->initView();
            return $this->_view;
        }

        public function getEnvironnement()
        {
            return $this->_environnement;
        }

        public function getSession()
        {
            return $this->_session;
        }

        /**
         *
         * @return \Sohoa\Framework\ErrorHandler
         */
        public function getErrorHandler()
        {
            if ( !$this->_errorHandler) $this->initErrorHandler();
            return $this->_errorHandler;
        }

        /**
         * Initialize the router : create the default router if not already given, and inject framework to the router
         * @return \Sohoa\Framework\Framework
         */
        public function initRouter()
        {

            if (!$this->_router) {

                $this->_router = new Router();
            }

            $this->_router->setFramework($this);

            return $this;
        }

        /**
         * Initialize the view : create the default view if not already given, and inject framework to the view
         * @return \Sohoa\Framework\Framework
         */
        public function initView()
        {

            if (!$this->_view) {

                $this->_view = new Greut();
            }

            $this->_view->setFramework($this);

            return $this;
        }

        /**
         * Initialize the dispatcher : create the basic dispatcher if not already given
         * @return \Sohoa\Framework\Framework
         */
        public function initDispatcher()
        {

            if (!$this->_dispatcher) {

                $this->_dispatcher = new Basic();
            }

            return $this;
        }

        /**
         * Initialize the error handler : create the default error handler if not already given, and inject framework to error handler
         * @return \Sohoa\Framework\Framework
         */
        public function initErrorHandler()
        {

            if (!$this->_errorHandler) {

                $this->_errorHandler = new ErrorHandler();
            }

            $this->_errorHandler->setFramework($this);

            return $this;
        }

        /**
         * Initialize all the kits : inject router and view to each kit
         * @return \Sohoa\Framework\Framework
         */
        public function initKit()
        {
            /* @var $kit Kitable */
            foreach ($this->_kit as $kit) {
                $kit->setView($this->getView());
                $kit->setRouter($this->getRouter());
            }

            return $this;
        }

        public function run()
        {

            $this->initRouter()
                ->initView()
                ->initDispatcher()
                ->initErrorHandler()
                ->initKit();

            $this->_router->construct();

            try {
                $this->_dispatcher->dispatch($this->_router, $this->_view, $this);
            } catch (\Exception $e) {

                $this->_errorHandler->manageError($e);
                $this->_dispatcher->dispatch($this->_errorHandler->getRouter(), $this->_view, $this);
            }
        }

        public function kit($identifier, Kitable $object = null)
        {

            if (empty($identifier))
                throw new \Exception('Kit identifier can\'t be empty');

            if ($object === null)
                if (array_key_exists($identifier, $this->_kit))
                    return $this->_kit[$identifier];
                else
                    throw new Exception('Kit "' . $identifier . '" has not been set');

            $object->setRouter($this->getRouter());
            $object->setView($this->getView());

            $this->_kit[$identifier] = $object;

            return $this->_kit[$identifier];
        }

        public function hasKit($identifier)
        {
            return array_key_exists($identifier, $this->_kit);
        }

        public function getKits()
        {
            return $this->_kit;
        }

        public static function services($identifier, $object = null)
        {

            trigger_error('Framework::services has been deprecated (' . $identifier . ')', E_USER_DEPRECATED);
        }

    }

}
