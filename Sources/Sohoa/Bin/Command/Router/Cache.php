<?php
namespace Sohoa\Bin\Command\Router {

    use Hoa\Console\Chrome\Text;
    use Sohoa\Framework\Framework;
    use Sohoa\Framework\Router;
    use Hoa\Core\Core;

    class Cache extends \Hoa\Console\Dispatcher\Kit
    {
        protected $options = array(
            array('generate', \Hoa\Console\GetOption::NO_ARGUMENT, 'g'),
            array('reset', \Hoa\Console\GetOption::NO_ARGUMENT, 'r'),
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, 'h'),
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, '?'),
        );

        /**
         * The entry method.
         *
         * @access  public
         * @return  int
         */
        public function main()
        {
            $reset    = false;
            $generate = false;
            $cache    = 'hoa://Application/Cache/Route.php';
            $route    = 'hoa://Application/Config/Route.php';

            while (false !== $c = $this->getOption($v)) {
                switch ($c) {
                case 'g':
                    $generate = true;
                case 'r':
                    $reset = true;
                    break;
                case 'h':
                case '?':
                    return $this->usage();
                    break;
            }
            }

            if ($reset === true) {
                if (file_exists($cache)) {
                    $result = unlink($cache);
                    if ($result) {
                        echo \Hoa\Console\Chrome\Text::colorize('[OK] Cache flush', 'foreground(green)')."\n";
                    } else {
                        echo \Hoa\Console\Chrome\Text::colorize('[!!] Cache flush', 'foreground(white) background(red)')."\n";
                    }
                } else {
                    echo \Hoa\Console\Chrome\Text::colorize('No cache found', 'foreground(green)')."\n";
                }
            }

            if ($generate === true) {
                $core           = Core::getInstance();
                $parameters     = $core->getParameters();
                $cwd            = $parameters->getKeyword('cwd');
                $parameters->setKeyword('cwd', $cwd.'/Public');
                $framework      = new Framework();
                $router         = $framework->getRouter();
                $dir            = dirname($cache);

                $router->saveCache($cache);
                echo 'Save your router result in'.\Hoa\Console\Chrome\Text::colorize(resolve($cache), 'foreground(green)')."\n";
            }

            if ($reset === false && $generate === false) {
                return $this->usage();
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
            echo '   Router:Cache '."\n\n";

            echo \Hoa\Console\Chrome\Text::colorize('Options:', 'fg(yellow)'), "\n";
            echo $this->makeUsageOptionsList(array(
                'help'     => 'This help.',
                'generate' => 'Generate Route cache',
                'reset'    => 'Router cache',
            ));

            return;
        }
    }
}

__halt_compiler();
Manage router cache
