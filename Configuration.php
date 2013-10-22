<?php

namespace Sohoa\Framework {
    use Hoa\Core\Parameter\Parameter;
    use Hoa\Core\Parameter\Parameterizable;
    use Hoa\File\Read;

    class Configuration implements Parameterizable
    {
        /**
         * @var \Hoa\Core\Parameter\Parameter
         */
        private $_parameter = null;

        public function __construct(Array $parameter = array())
        {
            $this->_parameter = new Parameter($this, array(), array(
                'bootstrap.configfile' => 'hoa://Application/config.json'
            ));
            $this->_parameter->setParameters($parameter);
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
            return $this->_parameter;
        }

        /**
         * @param string $file
         */
        protected function loadConfigurationFiles($file = null)
        {

            $config = ($file === null) ? $this->getParameters()->getParameter('bootstrap.configfile') : $file;
            if (is_array($config))
                foreach ($config as $file)
                    $this->loadConfigurationFiles($file);

            if (!is_string($config))
                return;

            if (file_exists($config)) {
                $file = new Read($config);
                if ($file->isFile() === true) {
                    $file = $file->readAll();
                    if ($file !== '') {
                        $json = json_decode($file, true);
                        foreach ($json as $key => $value)
                            if ($key === 'require')
                                $this->loadConfigurationFiles($value);
                            else
                                $this->getParameters()->setParameter($key, $value);
                    }
                }
            }
            return;
        }

    }
}
