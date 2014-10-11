<?php
/**
 * Created by PhpStorm.
 * User: Camael24
 * Date: 07/02/14
 * Time: 10:52
 */
namespace Sohoa\Framework\Dispatcher {
    use Hoa\Router\Router;
    use Hoa\View\Viewable;
    use Sohoa\Framework\Dispatcher as SoDispatch;
    use Sohoa\Framework\Framework;

    class Basic extends SoDispatch
    {
        protected function resolve(array $rule, Router $router, Viewable $view, Framework $framework)
        {

            $called = null;
            $variables = &$rule[\Hoa\Router::RULE_VARIABLES];
            $call = isset($variables['controller'])
                ? $variables['controller']
                : (isset($variables['_call'])
                    ? $variables['_call']
                    : $rule[\Hoa\Router::RULE_CALL]);
            $able = isset($variables['action'])
                ? $variables['action']
                : (isset($variables['_able'])
                    ? $variables['_able']
                    : $rule[\Hoa\Router::RULE_ABLE]);
            $rtv = array($router, $this, $view , $framework);
            $arguments = array();
            $reflection = null;

            if ($call instanceof \Closure) {

                $kitname = $this->getKitName();

                if (!empty($kitname)) {

                    $kit = dnew($this->getKitName(), $rtv);

                    if(!($kit instanceof Kit))
                        throw new Exception(
                            'Your kit %s must extend Hoa\Dispatcher\Kit.',
                            0, $kitname);

                    $variables['_this'] = $kit;
                }

                $called = $call;
                $reflection = new \ReflectionMethod($call, '__invoke');

                foreach ($reflection->getParameters() as $parameter) {

                    $name = strtolower($parameter->getName());

                    if (true === array_key_exists($name, $variables)) {

                        $arguments[$name] = $variables[$name];
                        continue;
                    }

                    if(false === $parameter->isOptional())
                        throw new Exception(
                            'The closured action for the rule with pattern %s needs ' .
                            'a value for the parameter $%s and this value does not ' .
                            'exist.',
                            1, array($rule[\Hoa\Router::RULE_PATTERN], $name));
                }
            } elseif (is_string($call) && null === $able) {

                $kitname = $this->getKitName();

                if (!empty($kitname)) {

                    $kit = dnew($this->getKitName(), $rtv);

                    if(!($kit instanceof Kit))
                        throw new Exception(
                            'Your kit %s must extend Hoa\Dispatcher\Kit.',
                            2, $kitname);

                    $variables['_this'] = $kit;
                }

                $reflection = new \ReflectionFunction($call);

                foreach ($reflection->getParameters() as $parameter) {

                    $name = strtolower($parameter->getName());

                    if (true === array_key_exists($name, $variables)) {

                        $arguments[$name] = $variables[$name];
                        continue;
                    }

                    if(false === $parameter->isOptional())
                        throw new Exception(
                            'The functional action for the rule with pattern %s needs ' .
                            'a value for the parameter $%s and this value does not ' .
                            'exist.',
                            3, array($rule[\Hoa\Router::RULE_PATTERN], $name));
                }
            } else {

                $async = $router->isAsynchronous();
                $controller = $call;
                $action = $able;

                if (!is_object($call)) {

                    if (false === $async) {

                        $_controller = 'synchronous.controller';
                        $_action = 'synchronous.action';
                    } else {

                        $_controller = 'asynchronous.controller';
                        $_action = 'asynchronous.action';
                    }

                    $this->_parameters->setKeyword('controller', $controller);
                    $this->_parameters->setKeyword('action', $action);

                    $controller = $this->_parameters->getFormattedParameter($_controller);
                    $action = $this->_parameters->getFormattedParameter($_action);

                    try {

                        $controller = dnew($controller, $rtv);
                    } catch ( \Exception $e ) {

                        throw new Exception(
                            'Controller %s is not found ' .
                            '(method: %s, asynchronous: %s).',
                            4, array($controller, strtoupper($router->getMethod()),
                                true === $async ? 'true': 'false'), $e);
                    }

//                    $kitname = $this->getKitName();

                    if(!empty($kitname))
                        $variables['_this'] = dnew($kitname, $rtv);

                    if(method_exists($controller, 'construct'))
                        $controller->construct();
                }

                if(!method_exists($controller, $action))
                    throw new Exception(
                        'Action %s does not exist on the controller %s ' .
                        '(method: %s, asynchronous: %s).',
                        5, array($action, get_class($controller),
                            strtoupper($router->getMethod()),
                            true === $async ? 'true': 'false'));

                $called = $controller;
                $reflection = new \ReflectionMethod($controller, $action);

                foreach ($reflection->getParameters() as $parameter) {

                    $name = strtolower($parameter->getName());

                    if (true === array_key_exists($name, $variables)) {

                        $arguments[$name] = $variables[$name];
                        continue;
                    }

                    if(false === $parameter->isOptional())
                        throw new Exception(
                            'The action %s on the controller %s needs a value for ' .
                            'the parameter $%s and this value does not exist.',
                            6, array($action, get_class($controller), $name));
                }
            }

            if($reflection instanceof \ReflectionFunction)
                $return = $reflection->invokeArgs($arguments);
            elseif($reflection instanceof \ReflectionMethod)
                $return = $reflection->invokeArgs($called, $arguments);

            return $return;

        }

    }
}
