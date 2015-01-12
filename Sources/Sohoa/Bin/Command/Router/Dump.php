<?php
namespace Sohoa\Bin\Command\Router {

    use Hoa\Console\Chrome\Text;
    use Sohoa\Framework\Framework;
    use Sohoa\Framework\Router;
    use Hoa\Core\Core;

    class Dump extends \Hoa\Console\Dispatcher\Kit
    {
        protected $options = array(
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
            $command = null;

            while (false !== $c = $this->getOption($v)) {
                switch ($c) {

                    case 'h':
                    case '?':
                        return $this->usage();
                        break;
                }
            }
            $cache      = 'hoa://Application/Cache/Route.php';
            $route      = 'hoa://Application/Config/Route.php';

            $core       = Core::getInstance();
            $parameters = $core->getParameters();
            $cwd        = $parameters->getKeyword('cwd');
            $parameters->setKeyword('cwd', $cwd.'/Public');
            $framework  = new Framework();
            $router     = $framework->getRouter();
            $router->construct();

            echo '# Router rules in '.resolve($route)."\n\n";
            echo Text::columnize($router->dump())."\n\n";

            return;
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
            echo '   Router:Dump '."\n\n";

            echo \Hoa\Console\Chrome\Text::colorize('Options:', 'fg(yellow)'), "\n";
            echo $this->makeUsageOptionsList(array(
                'help' => 'This help.',
            ));

            return;
        }
    }
}

__halt_compiler();
View Routes routes configuration
