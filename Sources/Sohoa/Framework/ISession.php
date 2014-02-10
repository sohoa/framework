<?php

namespace Sohoa\Framework {

  interface ISession extends \ArrayAccess
  {
      public function __set($name, $value);

      public function __get($name);

      public function __isset($name);

      public function __unset($name);

  }
}
