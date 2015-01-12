<?php
namespace Sohoa\Bin\Command {

    use Hoa\Console\Chrome\Text;

    abstract class Generate extends \Hoa\Console\Dispatcher\Kit
    {
        protected $verbose = false;
        protected $force = false;
        protected $dry = false;

        protected function log($text, $bool)
        {
            if ($this->verbose === true) {
                $this->status($text, $bool);
            }
        }

        protected function dir($dir)
        {
            if (is_dir($dir)) {
                $this->log($dir, true);
            } else {
                $this->log($dir, false);
                mkdir($dir);
                $this->log('Create : '.$dir, true);
            }
        }

        protected function _file($filename, $data)
        {
            if ($this->dry === true) {
                $this->status($filename, null);
                if ($this->verbose === true) {
                    echo $data."\n";
                }
            } else {
                if (file_exists($filename) === true and $this->force === false) {
                    $this->status($filename.' ever exists', false);
                } else {
                    if (file_exists($filename)) {
                        unlink($filename);
                    }
                    $this->status($filename, true);
                    file_put_contents($filename, $data);
                }
            }
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
                'with-view' => 'Generate associated view',
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
