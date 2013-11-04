<?php

namespace Sohoa\Framework\Tests\Unit;

use mageekguy\atoum;

require_once __DIR__ . '/../../vendor/autoload.php';

atoum\autoloader::get()->addDirectory(__NAMESPACE__ . '\Asserters', __DIR__ . '/Asserters');
