<?php
/**
 * Created by PhpStorm.
 * User: Camael24
 * Date: 16/01/14
 * Time: 17:22
 */
namespace Sohoa\Bin\Command\Core {

    use Hoa\Console\Chrome\Text;
    use Hoa\File\Finder;

    class Welcome extends \Hoa\Console\Dispatcher\Kit
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

            echo $this->stylize('List of Your application commands', 'h1'), "\n\n";
            $application = 'hoa://Application/Bin/Command';
            $this->listCommands($application);

            echo "\n\n" . $this->stylize('List of Sohoa commands', 'h1'), "\n\n";

            $sohoa = realpath(__DIR__ . '/../');
            $this->listCommands($sohoa);

            return;
        }

        protected function listCommands($directory)
        {
            $finder = new Finder();

            $finder->in($directory)->name('#(.*)\.php#');

            $group  = array();
            $out    = array();
            $extact = function ($uri) {
                $lines       = file($uri);
                $description = '';
                // Berk…
                for ($i = count($lines) - 1; $i >= 0; --$i) {

                    if (strpos($lines[$i] , '__halt_compiler();') === 0) {

                        $description = trim(implode(
                            '',
                            array_slice($lines, $i + 1)
                        ));
                        break;
                    }
                }

                unset($lines);

                return $description;

            };

            foreach ($finder as $cmd) {
                /**
                 * @var \SplFileInfo $cmd
                 */

                $category    = substr($cmd->getPath(), strrpos($cmd->getPath(), '\\') + 1, strlen($cmd->getPath()));
                $command     = $cmd->getBasename('.php');
                $description = $extact($cmd->getPathname());

                $group[$category][$command] = $description;

            }

            foreach ($group as $category => $command) {
                $out[] = $this->stylize($category, 'h2');
                foreach ($command as $name => $description)
                    $out[] = array('   ', $this->stylize($name, 'command') , $description);

            }

            echo Text::columnize($out);

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
            echo '   Welcome ' . "\n\n";

            echo $this->stylize('Options:', 'h1'), "\n";
            echo $this->makeUsageOptionsList(array(
                'help' => 'This help.'
            ));

            return;
        }
    }
}

__halt_compiler();
This Page
