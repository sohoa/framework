<?php

/**
 * Se mettre d'accord sur la doc.
 */

namespace Sohoa\Framework\View {

    use Sohoa\Framework\Kit;


    class Xyl extends \Hoa\Xyl\Xyl
    {


        public function __construct(\Hoa\Stream\IStream\In $in, \Hoa\Stream\IStream\Out $out, \Hoa\Xyl\Interpreter $interpreter,
            \Hoa\Router\Http $router = null, $entityResolver = null, Array $parameters = array())
        {

            Kit::add('xyl', new Kit\Xyl());
            parent:: __construct($in, $out, $interpreter, $router, $entityResolver, $parameters);
        }
    }
}
