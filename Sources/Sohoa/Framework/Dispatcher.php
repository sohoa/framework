<?php
/**
 * Created by PhpStorm.
 * User: Camael24
 * Date: 07/02/14
 * Time: 10:47
 */
namespace Sohoa\Framework {
    use Hoa\Core\Parameter\Parameter;
    use Hoa\Core\Parameter\Parameterizable;
    use Hoa\Router\Router as Routing;
    use Hoa\View\Viewable;

    abstract class Dispatcher implements Parameterizable
    {

        protected $_parameters  = null;
        protected $_currentView = null;
        protected $_kit         = 'Hoa\Dispatcher\Kit';

        public function __construct(array $parameters = array())
        {

            $this->_parameters = new Parameter(
                __CLASS__,
                array(
                    'controller' => 'main',
                    'action'     => 'main',
                    'method'     => null
                ),
                array(
                    'synchronous.controller'  => 'Application\Controller\(:controller:U:)',
                    'synchronous.action'      => '(:action:U:)Action',

                    'asynchronous.controller' => '(:%synchronous.controller:)',
                    'asynchronous.action'     => '(:%synchronous.action:)Async',

                    /**
                     * Router variables.
                     *
                     * 'variables.…' => …
                     */
                )
            );
            $this->_parameters->setParameters($parameters);

            return;
        }

        public function getParameters()
        {
            return $this->_parameters;
        }

        public function dispatch(Routing $router,
                                 Viewable $view , Framework $framework)
        {

            $rule = $router->getTheRule();

            if (null === $rule) {

                $router->route();
                $rule = $router->getTheRule();
            }

            $parameters        = $this->_parameters;
            $this->_parameters = clone $this->_parameters;

            foreach ($rule[Router::RULE_VARIABLES] as $key => $value)
                $this->_parameters->setParameter('variables.' . $key, $value);

            $this->_parameters->setKeyword('method', $router->getMethod());

            $out = $this->resolve($rule, $router, $view , $framework);
            unset($this->_parameters);
            $this->_parameters = $parameters;

            return $out;
        }

        abstract protected function resolve(array $rule, Routing $router, Viewable $view , Framework $framework);

    }
}
