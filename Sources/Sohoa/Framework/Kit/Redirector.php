<?php
namespace {

    from('Hoa')
        ->import('Dispatcher.Kit');
}

namespace Sohoa\Framework\Kit {

    class Redirector extends Kitable
    {
        public function redirect($ruleId, array $data = array(), $secured = null, $status = 302)
        {
            $uri = $this->router->unroute($ruleId, $data, $secured);

            $response = $this->view->getOutputStream();
            $response->sendHeader('Location', $uri, true, $status);

            exit;
        }
    }
}
