<?php
namespace Sohoa\Framework\Kit {
    class Kitable
    {
        protected $router;

        /**
         *
         * @var \Hoa\View\Viewable
         */
        protected $view;

        /**
         * @param mixed $router
         */
        public function setRouter(\Hoa\Router\Router $router)
        {
            $this->router = $router;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getRouter()
        {
            return $this->router;
        }

        /**
         * @param mixed $view
         */
        public function setView($view)
        {
            $this->view = $view;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getView()
        {
            return $this->view;
        }

    }
}
