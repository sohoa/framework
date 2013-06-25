<?php
    namespace {
        from('Hoa')->import('File.Read');
    }
    namespace Sohoa\Framework {
        /**
         * Class Bootstrap
         *
         * @package Sohoa\Framework
         */
        class Bootstrap implements \Hoa\Core\Parameter\Parameterizable
        {
            /**
             * Parameters.
             *
             * @var \Hoa\Core\Parameter object
             */
            protected $_parameters = null;

            /**
             * @param array $parameter
             */
            public function __construct(Array $parameter = array())
            {
                try {
                    $this->_parameters = new \Hoa\Core\Parameter($this, array(), array(
                        'bootstrap.configfile' => 'hoa://Application/Public/config.json'
                    ));
                    $this->_parameters->setParameters($parameter);
                    $this->loadConfigurationFiles();

                } catch (\Hoa\Core\Exception $e) {
                    var_dump($e->getFormattedMessage());
                }

            }

            /**
             * @param string $file
             */
            protected function loadConfigurationFiles($file = null)
            {

                $config = ($file === null) ? $this->_parameters->getParameter('bootstrap.configfile') : $file;
                if (is_array($config))
                    foreach ($config as $file)
                        $this->loadConfigurationFiles($file);

                if (!is_string($config))
                    return;

                $file = new \Hoa\File\Read($config);
                if ($file->isFile() === true) {
                    $file = $file->readAll();
                    if ($file !== '') {
                        $json = json_decode($file, true);
                        foreach ($json as $key => $value)
                            if ($key === 'require')
                                $this->loadConfigurationFiles($value);
                            else
                                $this->_parameters->setParameter($key, $value);
                    }
                }

                return;
            }

            /**
             * Get Default parameters.
             *
             * @access  public
             * @return  \Hoa\Core\Parameter
             */
            public function getParameters()
            {
                return $this->_parameters;
            }
        }
    }
