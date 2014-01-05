<?php

namespace Sohoa\Framework {

    class ErrorHandler
    {

        /**
         *
         * @var Router
         */
        protected $router;

        // ROUTE_* error types must be numeric @see addErrorRoute
        const ROUTE_ALL_ERROR       = 0;
        const ROUTE_ERROR_404       = 1;
        const ROUTE_ERROR_500       = 2;
        const ERROR_ROUTE_ID        = '--error--';
        const ERROR_404_ROUTE_ID    = '--err404--';
        const ERROR_500_ROUTE_ID    = '--err500--';
        const ERROR_CUSTOM_ROUTE_ID = '--';

        protected $routeId           = array(
            self::ROUTE_ALL_ERROR => self::ERROR_ROUTE_ID,
            self::ROUTE_ERROR_404 => self::ERROR_404_ROUTE_ID,
            self::ROUTE_ERROR_500 => self::ERROR_500_ROUTE_ID
        );
        protected $customErrorRoutes = array();

        public function handleErrorsAsException($error_types = null)
        {
            $error_types = ($error_types ? : (E_ALL | E_STRICT));
            set_error_handler(function ($errno, $errstr, $errfile, $errline) {
                if (error_reporting() !== 0) {
                    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
                }
            }, $error_types);
            if (0 !== ($error_types & E_ERROR)) {
                register_shutdown_function(function () {
                    $error = error_get_last();
                    if ($error["type"] == E_ERROR) {
                        throw new \ErrorException($error['messaege'], 0, $error['type'], $error['file'], $error['line']);
                    }
                });
            }
        }

        public function getRouter()
        {
            return $this->router;
        }

        public function setRouter(Router $router)
        {
            $this->router = $router;
        }

        /**
         * Add a route for error handling
         * @param string $errorType the type of error to route
         * @param string $actionName used to define the action to route to
         */
        public function routeError($errorType, $actionName)
        {
            if (true === is_numeric($errorType)) {

                $errorRouteId = $this->routeId[$errorType];
            } elseif (class_exists($errorType) && is_subclass_of($errorType, '\Exception')) {

                $errorRouteId = preg_replace('/\W/', '', self::ERROR_CUSTOM_ROUTE_ID . $errorType);
                $this->customErrorRoutes[] = $errorType;
                $this->routeId[$errorType] = $errorRouteId;
            } else {

                throw new Exception('Unable to add error route for %s', 0, $errorType);
            }

            $this->router->any($errorRouteId, array('as' => $errorRouteId, 'to' => $actionName));

            return $this;
        }

        /**
         * Detect which route to use when an exception occures
         * @param \Exception $error
         */
        public function manageError($error)
        {
            if ($error instanceof \Hoa\Router\Exception\NotFound) {

                $this->route(self::ROUTE_ERROR_404, $error);
            } elseif ($error instanceof \Hoa\Dispatcher\Exception) {

                switch ($error->getCode()) {

                    case 4:
                    case 5:
                        $this->route(self::ROUTE_ERROR_404, $error);
                        break;

                    default:
                        $this->route(self::ROUTE_ALL_ERROR, $error);
                        break;
                }
            } elseif (false === empty($this->customErrorRoutes)) {

                $routingDone = false;
                foreach ($this->customErrorRoutes as $className) {

                    if ($error instanceof $className) {

                        $routingDone = true;
                        $this->route($className, $error);
                    }
                }

                if (false === $routingDone) {

                    $this->route(self::ROUTE_ALL_ERROR, $error);
                }
            } else {

                $this->route(self::ROUTE_ALL_ERROR, $error);
            }
        }

        /**
         * Change the router state according to the error Code
         * @param string $errorRouteId
         * @param \Exception $error
         * @throws Exception
         * @throws \Exception
         */
        public function route($errorCode, $error)
        {
            // Router knows a route for this error code
            if (isset($this->routeId[$errorCode])) {

                $errorRouteId = $this->routeId[$errorCode];
            } else {

                $errorRouteId = null;
            }

            if ($this->router->ruleExists($errorRouteId)) {

                // There is a specific route for this error code
                $errorRoute = $errorRouteId;
            } elseif ($this->router->ruleExists(self::ROUTE_ALL_ERROR)) {

                // There is a default route for all errors
                $errorRoute = self::ROUTE_ALL_ERROR;
            } else {

                // No route for error, do nothing
                $errorRoute = null;
            }

            if ($errorRoute !== null) {

                try {

                    // Prepare redirecting in the router
                    $this->router->route($this->router->getPrefix() . '/' . $errorRoute);
                    $this->router->setVariable('err', $error);
                } catch (\Exception $e) {

                    // The router is unable to route the error
                    throw new Exception('Unable to handle error', 0, $e);
                }
            } else {

                // There is no error route for this error code, just transfert the exception
                throw $error;
            }
        }

    }

}
