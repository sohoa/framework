<?php
namespace Sohoa\Bin\Command\Generate {

    use Hoa\Console\Chrome\Text;
    use Hoa\Core\Core;
    use Sohoa\Framework\View\Greut;

    class Resource extends Controller
    {
        protected $options = array(
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, 'h'),
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, '?'),
            array('template', \Hoa\Console\GetOption::REQUIRED_ARGUMENT, 't'),
            array('except', \Hoa\Console\GetOption::REQUIRED_ARGUMENT, 'e'),
            array('only', \Hoa\Console\GetOption::REQUIRED_ARGUMENT, 'o'),
            array('without-model', \Hoa\Console\GetOption::NO_ARGUMENT, 'm'),
            array('without-view', \Hoa\Console\GetOption::NO_ARGUMENT, 'v'),
            array('with-async', \Hoa\Console\GetOption::NO_ARGUMENT, 'a'),
            array('dry-run', \Hoa\Console\GetOption::NO_ARGUMENT, 'd'),
            array('verbose', \Hoa\Console\GetOption::NO_ARGUMENT, 'V'),
            array('force', \Hoa\Console\GetOption::NO_ARGUMENT, 'f'),
        );
        protected $verbose = false;
        protected $force = false;
        protected $dry = false;
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
            $withModel  = true;
            $engine     = 'greut';
            $resource   = null;
            $action     = array();
            $except     = array();
            $only       = array();
            $sample     = array('index','new','show','create','edit','update','destroy');

            while (false !== $c = $this->getOption($v)) {
                switch ($c) {
                    case 'h':
                    case '?':
                        return $this->usage();
                        break;
                    case 'e':
                        $except = explode(',', $v);
                        break;
                    case 'o':
                        $only = explode(',', $v);
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
                    case 'm':
                        $withModel = false;
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

            $this->parser->listInputs($resource);

            if ($resource === null) {
                throw new \Hoa\Core\Exception\Exception("Resource argument is required", 0);
            }

            if (!empty($only) and !empty($except)) {
                throw new \Hoa\Core\Exception("Only and Except are incompatible", 1);
            }

            if (!empty($only)) {
                $action = $only;
            }

            if (!empty($except)) {
                $action = array();
                foreach ($sample as $value) {
                    if (in_array($value, $except) === false) {
                        $action[] = $value;
                    }
                }
            }

            if (empty($action)) {
                $action = $sample;
            }

            if ($this->verbose === true) {
                echo 'Force      : '.var_export($this->force, true)."\n";
                echo 'View       : '.var_export($withView, true)."\n";
                echo 'Async      : '.var_export($withAsync, true)."\n";
                echo 'Engine     : '.var_export($engine, true)."\n";
                echo 'Verbose    : '.var_export($this->verbose, true)."\n";
                echo 'Dry Run    : '.var_export($this->dry, true)."\n";
                echo "\n";
                echo 'Controller : '.var_export($resource, true)."\n";
                echo 'Actions    : '.var_export($action, true)."\n";
            }

            $this->check();
            $this->generateController($resource, $action, $withView, $withAsync, $engine, $this->getControllerPath());

            if ($withView === true) {
                $this->generateView($resource, $action, $engine, $this->getViewPath());
            }

            if ($withModel === true) {
                $this->generateModel($resource, $action, $this->getModelPath());
            }

            echo 'Add this line in your Application/Config/Route.php file'."\n";

            $_action = 'array("'.implode('","', $action).'")';

            echo "\t".'$this->resource("'.$resource.'", array("only" => '.$_action.'));';
        }

        protected function generateModel($resource, $action, $directory)
        {
            $view             = new Greut();
            $file             = $directory.ucfirst($resource).'.php';
            $data             = $view->getData();
            $tpl              = realpath(__DIR__.'/../../Template/');
            $data->resource   = $resource;
            $data->action     = $action;

            if ($tpl === false) {
                throw new \Hoa\Core\Exception\Exception('Template directory not found !', 1);
            }

            $this->_file($file, '<?php'."\n".$view->renderFile($tpl.'/Model.tpl.php')); // ?
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
                'without-model' => 'Don\'t generate associated model',
                'with-async' => 'Generate associated async action',
                'dry-run' => 'Run without file operation',
                'verbose' => 'Display log',
            ));

            return;
        }
    }
}

__halt_compiler();
Generate resource (Controller, Route, Model, View)
