<?php
namespace Sohoa\Bin\Command\Generate {

    use Hoa\Console\Chrome\Text;
    use Hoa\Core\Core;
    use Sohoa\Framework\View\Greut;

    class Controller extends \Sohoa\Bin\Command\Generate
    {
        protected $options = array(
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, 'h'),
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, '?'),
            array('template', \Hoa\Console\GetOption::REQUIRED_ARGUMENT, 't'),
            array('without-view', \Hoa\Console\GetOption::NO_ARGUMENT, 'v'),
            array('with-async', \Hoa\Console\GetOption::NO_ARGUMENT, 'a'),
            array('dry-run', \Hoa\Console\GetOption::NO_ARGUMENT, 'd'),
            array('verbose', \Hoa\Console\GetOption::NO_ARGUMENT, 'V'),
            array('force', \Hoa\Console\GetOption::NO_ARGUMENT, 'f'),
        );

        /**
         * The entry method.
         *
         * @access  public
         * @return  int
         */
        public function main()
        {
            $withView   = true;
            $withAsync  = false;
            $engine     = 'greut';
            $controller = null;
            $action     = null;

            while (false !== $c = $this->getOption($v)) {
                switch ($c) {
                    case 'h':
                    case '?':
                        return $this->usage();
                        break;
                    case 'V':
                        $this->verbose = true;
                        break;
                    case 'd':
                        $this->dry = true;
                        break;
                    case 't':
                        $engine = $v;
                        break;
                    case 'v':
                        $withView = false;
                        break;
                    case 'f':
                        $this->force = true;
                        break;
                    case 'a':
                        $withAsync = true;
                        break;
                }
            }

            $this->parser->listInputs($controller, $action);

            if ($action === null) {
                $action = 'index';
            }

            if ($controller === null) {
                throw new \Hoa\Core\Exception\Exception("Contoller argument is required", 0);
            }

            $action = explode(',', $action);

            if ($this->verbose === true) {
                echo 'Force      : '.var_export($this->force, true)."\n";
                echo 'View       : '.var_export($withView, true)."\n";
                echo 'Async      : '.var_export($withAsync, true)."\n";
                echo 'Engine     : '.var_export($engine, true)."\n";
                echo 'Verbose    : '.var_export($this->verbose, true)."\n";
                echo 'Dry Run    : '.var_export($this->dry, true)."\n";
                echo "\n";
                echo 'Controller : '.var_export($controller, true)."\n";
                echo 'Action     : '.var_export($action, true)."\n";
            }

            $this->check();
            $this->generateController($controller, $action, $withView, $withAsync, $engine, $this->getControllerPath());

            if ($withView === true) {
                $this->generateView($controller, $action, $engine, $this->getViewPath());
            }
        }

        protected function check()
        {
            $dir_application = 'hoa://Application/';
            $dir_controller  = $this->getControllerPath();
            $dir_view        = $this->getViewPath();
            $dir_model       = $this->getModelPath();

            $this->dir($dir_application);
            $this->dir($dir_controller);
            $this->dir($dir_view);
            $this->dir($dir_model);
        }

        public function getControllerPath()
        {
            return 'hoa://Application/Controller/';
        }

        public function getViewPath()
        {
            return 'hoa://Application/View/';
        }

        public function getModelPath()
        {
            return 'hoa://Application/Model/';
        }

        protected function generateController($controller, Array $action = array(), $withView = false, $withAsync = false, $engine = 'greut', $directory = 'hoa://Application/Controller/')
        {
            $view             = new Greut();
            $file             = $directory.ucfirst($controller).'.php';
            $data             = $view->getData();
            $tpl              = realpath(__DIR__.'/../../Template/Greut/');
            $data->controller = $controller;
            $data->action     = $action;
            $data->withView   = $withView;
            $data->engine     = $engine;
            $data->withAsync  = $withAsync;
            $status           = false;

            if ($tpl === false) {
                throw new \Hoa\Core\Exception\Exception('Template directory not found !', 1);
            }

            $this->_file($file, '<?php'."\n".$view->renderFile($tpl.'/Controller.tpl.php')); // ?
        }

        protected function generateView($controller, Array $action, $engine, $directory)
        {
            foreach ($action as $a) {
                $this->_genView($controller, $a, $engine, $directory);
            }
        }

        private function _genView($controller, $action, $engine, $directory)
        {
            $view             = new Greut();
            $directory        = $directory.ucfirst($controller).'/';
            $file             = $directory.ucfirst($action).'.tpl.php';
            $data             = $view->getData();
            $tpl              = realpath(__DIR__.'/../../Template/');
            $data->controller = $controller;
            $data->action     = $action;

            $this->dir($directory);

            if ($tpl === false) {
                throw new \Hoa\Core\Exception\Exception('Template directory not found !', 1);
            }

            if (is_dir($tpl.'/'.ucfirst($engine)) === false) {
                throw new \Hoa\Core\Exception\Exception('This engine template (%s) has not support yet !', 2, array($engine));
            }
            $this->_file($file, ''."\n ".$view->renderFile($tpl.'/'.ucfirst($engine).'/View.tpl.php')); // ?
        }

        /**
         * The command usage.
         *
         * @access  public
         * @return  int
         */
        public function usage()
        {
            echo \Hoa\Console\Chrome\Text::colorize('Usage:', 'fg(yellow)')."\n";
            echo '   Generate:Controller ControllerName [action1,action2,action3] [options]'."\n\n";

            echo \Hoa\Console\Chrome\Text::colorize('Options:', 'fg(yellow)'), "\n";
            echo $this->makeUsageOptionsList(array(
                'help' => 'This help.',
                'force' => 'Use force and erase local modification',
                'template' => 'Define Engine template',
                'without-view' => 'Don\'t generate associated view',
                'with-async' => 'Generate associated async action',
                'dry-run' => 'Run without file operation',
                'verbose' => 'Display log',
            ));

            return;
        }
    }
}

__halt_compiler();
Generate controller (Controller + View)
