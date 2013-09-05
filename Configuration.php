<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Julien
     * Date: 19/08/13
     * Time: 12:26
     * To change this template use File | Settings | File Templates.
     */
    namespace Sohoa\Framework {
        class Configuration implements \Hoa\Core\Parameter\Parameterizable
        {
            /**
             * @var \Hoa\Core\Parameter
             */
            private $_parameters = null;

            public function __construct(Array $parameter = array())
            {
                $this->_parameters = new \Hoa\Core\Parameter($this, array(), array(
                    'bootstrap.configfile' => 'hoa://Application/Public/config.json'
                ));
                $this->_parameters->setParameters($parameter);
                $this->loadConfigurationFiles();
            }

            /**
             * Get parameters.
             *
             * @access  public
             * @return  \Hoa\Core\Parameter
             */
            public function getParameters()
            {
                return $this->_parameters;
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

                if (file_exists($config)) {
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
                }
                return;
            }

        }
    }
