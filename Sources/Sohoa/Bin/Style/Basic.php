<?php

namespace Sohoa\Bin\Style {

    /**
     *
     * This sheet declares the main style.
     *
     * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
     * @copyright  Copyright © 2007-2013 Ivan Enderlin.
     */

    class Basic extends \Hoa\Console\Chrome\Style
    {
        /**
         * Import the style.
         *
         * @access  public
         * @return void
         */
        public function import()
        {
            parent::addStyles(array(

                '_exception' => array(
                    parent::COLOR_FOREGROUND_WHITE,
                    parent::COLOR_BACKGROUND_RED
                ),

                'h1'         => array(
                    parent::COLOR_FOREGROUND_WHITE,
                    parent::COLOR_BACKGROUND_RED
                ),

                'h2'         => array(
                    parent::COLOR_FOREGROUND_GREEN
                ),
                'h3'         => array(
                    parent::COLOR_FOREGROUND_VIOLET
                ),
                'info'       => array(
                    parent::COLOR_FOREGROUND_YELLOW
                ),

                'error'      => array(
                    parent::COLOR_FOREGROUND_WHITE,
                    parent::COLOR_BACKGROUND_RED,
                    parent::TEXT_BOLD
                ),

                'success'    => array(
                    parent::COLOR_FOREGROUND_WHITE,
                    parent::COLOR_FOREGROUND_GREEN,
                    parent::TEXT_BOLD
                ),

                'nosuccess'  => array(
                    parent::COLOR_FOREGROUND_RED
                ),

                'command'    => array(
                    parent::COLOR_FOREGROUND_BLUE
                ),

                'attention'  => array(
                    parent::COLOR_FOREGROUND_WHITE,
                    parent::COLOR_BACKGROUND_RED,
                    parent::TEXT_BOLD
                ),

            ));

            return;
        }
    }

}
