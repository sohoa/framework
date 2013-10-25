<?php

namespace Sohoa\Framework {
    use Hoa\Core\Parameter\Parameter;

    /**
     * Class Framework
     *
     * @package Sohoa\Framework
     */
    class Framework
    {

        /**
         * @var Configuration object
         */
        protected $_parameters = null;

        /**
         * @var \Hoa\Router\Router|string
         */
        public $router = null;

        /**
         * @var \Hoa\Dispatcher\Dispatcher|string
         */
        public $dispatcher = null;

        /**
         * @var \Hoa\View\Viewable|string
         */
        public $view = null;

        /**
         *
         * On définie le router , le dispatcher, et la vue avec les valeurs contenues dans le fichier de configuration ,
         * ou par des valeurs par défault.
         *
         * Router       : bootstrap.router.handler
         * Dispatcher   : bootstrap.dispatcher.handler
         * View         : est définie manuellement par l'utilisateur
         *
         * Utiliser la valeur bootstrap.configfile pour changer le fichier de configuration chargé automatiquement
         *
         * @param array $parameter
         */
        public function __construct(Array $parameter = array())
        {
            try {
                $this->_parameters = new Configuration($parameter);
                $core              = \Hoa\Core\Core::getInstance();
                $parameter         = $core->getParameters();
                $router            = $this->getParameters()->getParameter('bootstrap.router.handler');
                $this->router      = ($router === null) ? '\Hoa\Router\Http' : $router;
                $dispatcher        = $this->getParameters()->getParameter('bootstrap.dispatcher.handler');
                $this->dispatcher  = ($dispatcher === null) ? '\Hoa\Dispatcher\Basic' : $dispatcher;


                if ($parameter instanceof Parameter) {

                    $parameter->setParameter('protocol.Application', '(:cwd:h:)/Application/');
                    $parameter->setParameter('protocol.Public', '(:%root.application:)/Public/');
                    $parameter->setParameter('namespace.prefix.Application', '(:cwd:h:)/');

                }
                $core->setProtocol();


            } catch (\Hoa\Core\Exception $e) {
                var_dump($e->getFormattedMessage());
            }

        }

        /**
         * @param string $file
         */


        /**
         * Get Default parameters.
         *
         * @access  public
         * @return  \Hoa\Core\Parameter
         */
        public function getParameters()
        {
            return $this->_parameters->getParameters();
        }

        public function run()
        {
            try {

                $dispatcher = $this->dispatcher;
                $router     = $this->router;
                $view       = $this->view;
                if (is_string($router))
                    $router = dnew($router);

                if (is_string($dispatcher))
                    $dispatcher = dnew($dispatcher);

                if (is_string($view))
                    $view = dnew($view);

                $dispatcher->dispatch($router, $view);

            } catch (\Hoa\Core\Exception $e) {
                var_dump($e->getFormattedMessage());
            }

        }

    }
}
