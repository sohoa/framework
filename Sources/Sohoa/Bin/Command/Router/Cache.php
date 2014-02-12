<?php
/**
 * Created by PhpStorm.
 * User: Camael24
 * Date: 16/01/14
 * Time: 17:22
 */
namespace Sohoa\Bin\Command\Router {

    use Sohoa\Framework\Framework;
    use Sohoa\Framework\Router;

    class Cache extends \Hoa\Console\Dispatcher\Kit
    {

        /**
         * Options description.
         *
         * @var \Hoa\Core\Bin\Welcome array
         */
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

            while (false !== $c = $this->getOption($v)) switch ($c) {
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

            if ($reset === true)
                if (file_exists($cache)) {
                    $result = unlink($cache);
                    if ($result)
                        echo 'Purge du cache [ok]' . "\n";
                    else
                        echo 'Purge du cache [fail]' . "\n";
                } else {
                    echo 'No cache found' . "\n";
                }

            if ($generate === true) {

                $router       = new Router();
                $this->router = $router;
                $dir          = dirname($cache);
                Framework::services('router', $router); // Fuck IOC

                require_once $route;

                if (is_dir($dir) === false)
                    mkdir($dir);

                $router->saveCache($cache);
                echo 'Save your router result in ' . resolve($cache);
            }

            if ($reset === false && $generate === false)
                return $this->usage();
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
            echo '   Router:Cache ' . "\n\n";

            echo $this->stylize('Options:', 'h1'), "\n";
            echo $this->makeUsageOptionsList(array(
                'help'     => 'This help.',
                'generate' => 'Generate Route cache',
                'reset'    => 'Router cache'
            ));

            return;
        }
    }
}

__halt_compiler();
Manage router cache
