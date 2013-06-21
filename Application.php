<?php
    namespace {
        from('Sohoa')->import('Framework.Bootstrap');
    }
    namespace Sohoa\Framework {
        class Application
        {
            /**
             * @var Bootstrap
             */
            private $_bootstrap = null;
            /**
             * @var Application
             */
            private $_parent = null;

            final public function  __construct(Application $_this = null)
            {
                $this->_parent = $_this;
            }

            public function addBootstrap($bootFile = null, $namespace = '\\')
            {
                if ($this->_bootstrap === null)
                    $this->_bootstrap = Bootstrap::getInstance();

                $this->_bootstrap->load($bootFile, $namespace, $this);

            }

        }
    }
