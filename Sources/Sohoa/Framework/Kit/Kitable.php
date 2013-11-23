<?php
namespace Sohoa\Framework\Kit {
    class Kitable
    {
        protected $router;
        protected $view;

        /**
         * @param mixed $router
         */
        public function setRouter($router)
        {
            $this->router = $router;
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
 