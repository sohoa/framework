<?php
namespace {
    require_once __DIR__ . '/../../Runner.php';
}

namespace Sohoa\Framework\View\Tests\Unit {

    use Hoa\Stringbuffer\ReadWrite;
    use Sohoa\Framework\Framework;
    use Sohoa\Framework\Kit as _Kit;

    class Greut extends \atoum\test
    {
        public function testFirst()
        {
            $view = new \Sohoa\Framework\View\Greut();
            $data = $view->getData();
            $view->setPath(dirname(dirname(dirname(__FILE__))) . "/Template"); // Impossible de tester avec le protocol hoa://


            $data->title   = 'Title';
            $data->lang    = 'FR-fr';
            $data->foo     = 'Bar';
            $data->charset = 'utf-8';
            $output        = $view->renderFile('./index.tpl.php');
            $header        = $view->getHeaders();

            $this->string($output)->hasLengthGreaterThan(1449);
            $this->sizeof($header)->isIdenticalTo(1);
            $this->sizeof($header[0])->isIdenticalTo(4);

            $this->exception(function () use ($view) {
                $view->renderFile('./anFileWhoNotExists.php');

            });

        }
    }
}