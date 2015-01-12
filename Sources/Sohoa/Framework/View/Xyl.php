<?php

/**
 * Se mettre d'accord sur la doc.
 */

namespace Sohoa\Framework\View {

    use Hoa\Router\Router;
    use Sohoa\Framework\Framework;
    use Sohoa\Framework\Kit;

    class Xyl extends \Hoa\Xyl\Xyl implements Soview
    {
        public $framework = null;

        public function setRouter(Router $router)
        {
        }

        public function setFramework(Framework $framework)
        {
            $this->_framework = $framework;
            $framework->kit('xyl', new Kit\Xyl());

            return $this;
        }
    }
}
