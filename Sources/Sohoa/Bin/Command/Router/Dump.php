<?php
/**
 * Created by PhpStorm.
 * User: Camael24
 * Date: 16/01/14
 * Time: 17:22
 */
namespace Sohoa\Bin\Command\Router {

    use Hoa\Console\Chrome\Text;
    use Sohoa\Framework\Framework;
    use Sohoa\Framework\Router;

    class Dump extends \Hoa\Console\Dispatcher\Kit
    {

        /**
         * Options description.
         *
         * @var \Hoa\Core\Bin\Welcome array
         */
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

            while (false !== $c = $this->getOption($v)) switch ($c) {

                case 'h':
                case '?':
                    return $this->usage();
                    break;
            }
            $cache = 'hoa://Application/Cache/Route.php';
            $route = 'hoa://Application/Config/Route.php';

            $router       = new Router();
            $this->router = $router;
            Framework::services('router', $router); // Fuck IOC

            require_once $route;

            echo '# Router rules in ' . resolve($route) . "\n\n";
            echo Text::columnize($router->dump())."\n\n";

            $router       = new Router();
            $this->router = $router;
            $this->router->loadCache($cache);
            Framework::services('router', $router); // Fuck IOC

            echo '# Router rules in ' . resolve($cache) . "\n\n";
            echo Text::columnize($router->dump());

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
            echo $this->stylize('Usage:', 'h1') . "\n";
            echo '   Router:Dump ' . "\n\n";

            echo $this->stylize('Options:', 'h1'), "\n";
            echo $this->makeUsageOptionsList(array(
                'help' => 'This help.'
            ));

            return;
        }
    }
}

__halt_compiler();
View Routes routes configuration
