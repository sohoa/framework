<?php
namespace Sohoa\Framework {

    use Hoa\Router\Router as HRouter;
    use Hoa\View\Viewable;

    class Kit extends \Hoa\Dispatcher\Kit
    {
        public $framework = null;

        public function __construct(HRouter $router, Dispatcher $dispatcher, Viewable $view, Framework $framework)
        {

            $this->framework  = $framework;
            $this->router     = $router;
            $this->dispatcher = $dispatcher;
            $this->view       = $view;
            $this->env        = $this->framework->getEnvironnement();

            if (null !== $view)
                $this->data = $view->getData();

            return;
        }

        public function __get($key)
        {
            return $this->framework->kit($key);
        }

    }
}
