<?php

/**
 *
 * @author Guile
 */

namespace Sohoa\Framework\View {

    class Helper
    {
        /**
         *
         * @var \Hoa\View\Viewable
         */
        protected $view;

        /**
         *
         * @var \Hoa\Core\Data
         */
        protected $data;

        /**y
         */
        public function setView(\Hoa\View\Viewable $view) {
            $this->view = $view;
            $this->data = $view->getData();
        }
    }

}
