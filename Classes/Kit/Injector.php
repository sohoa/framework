<?php
namespace Sohoa\Framework\Kit {

    class Injector extends Kitable
    {
		/**
		 *
		 * @var mixed Object to inject in the Controller
		 */
		protected $object;

		public function __construct($object)
		{
			$this->object = $object;
		}

		public function getObject()
		{
			return $this->object;
		}

		public function setObject($object)
		{
			$this->object = $object;
		}

		public function __call($name, $arguments)
		{
			$cb = array($this->object, $name);
			if (is_callable($cb)) {
				return call_user_func_array($cb, $arguments);
			} else {
				throw new \Sohoa\Framework\Exception('Unable to call %s::%s() kit method', 0, array(get_class($this->object), $name));
			}
		}

		public function __get($name)
		{
			if (property_exists($this->object, $name)) {
				return $this->object->$name;
			} elseif (method_exists($this->object, '__get')) {
				return $this->object->__get($name);
			} else {
				throw new \Sohoa\Framework\Exception('Unable to get %s::%s kit property', 0, array(get_class($this->object), $name));
			}
		}

		public function __set($name, $value)
		{
			if (property_exists($this->object, $name)) {
				$this->object->$name = $value;
			} elseif (method_exists($this->object, '__set')) {
				return $this->object->__set($name, $value);
			} else {
				throw new \Sohoa\Framework\Exception('Unable to set %s::%s kit property', 0, array(get_class($this->object), $name));
			}

		}


		public function __invoke()
		{
			if (is_callable($this->object)) {
				return call_user_func_array($this->object, func_get_args());
			} else {
				throw new \Sohoa\Framework\Exception('Unable to invoke kit %s()', 0, get_class($this->object));
			}
		}

    }
}
