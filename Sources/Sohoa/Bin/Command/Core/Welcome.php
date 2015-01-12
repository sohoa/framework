<?php
namespace Sohoa\Bin\Command\Core {

    use Hoa\Console\Chrome\Text;
    use Hoa\File\Finder;

    class Welcome extends \Hoa\Console\Dispatcher\Kit
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

            echo \Hoa\Console\Chrome\Text::colorize('List of Your application commands', 'fg(yellow)'), "\n\n"; //('List of Your application commands', 'h1'), "\n\n";

            $application = 'hoa://Application/Bin/Command';
            $this->listCommands($application);

            echo "\n\n".\Hoa\Console\Chrome\Text::colorize('List of Sohoa commands', 'fg(yellow)'), "\n\n";

            $sohoa = realpath(__DIR__.'/../');
            $this->listCommands($sohoa);

            return;
        }

        protected function listCommands($directory)
        {
            if (is_dir($directory) === false) {
                return array();
            }

            $finder = new Finder();
            $finder->in($directory)->name('#(.*)\.php#');

            $group  = array();
            $out    = array();
            $extact = function ($uri) {
                $lines       = file($uri);
                $description = '';
                // Berk…
                for ($i = count($lines) - 1; $i >= 0; --$i) {
                    if (strpos($lines[$i], '__halt_compiler();') === 0) {
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

                $path                       = str_replace('\\', '/', $cmd->getPath());
                $category                   = substr($path, strrpos($path, '/') + 1, strlen($path));
                $command                    = $cmd->getBasename('.php');
                $description                = $extact($cmd->getPathname());
                $group[$category][$command] = $description;
            }

            foreach ($group as $category => $command) {
                $out[] = \Hoa\Console\Chrome\Text::colorize($category, 'fg(green)');
                foreach ($command as $name => $description) {
                    $out[] = array('   ', \Hoa\Console\Chrome\Text::colorize($name, 'fg(blue)'), $description);
                }
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
            echo \Hoa\Console\Chrome\Text::colorize('Usage:', 'fg(yellow)')."\n";
            echo '   Welcome '."\n\n";

            echo \Hoa\Console\Chrome\Text::colorize('Options:', 'fg(yellow)'), "\n";
            echo $this->makeUsageOptionsList(array(
                'help' => 'This help.',
            ));

            return;
        }
    }
}

__halt_compiler();
This Page
