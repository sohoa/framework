<?php

namespace Sohoa\Framework\Session {
    use Sohoa\Framework\ISession;
    use \Hoa\Session\Session as HSession;

    class Session extends HSession implements ISession
    {
        public function __construct($namespace = '_default', $cache = null,
                                    $cacheExpire = null)
        {
            parent::__construct($namespace,$cache,$cacheExpire);
        }

        /**
         * Check if a data exists. Alias of offsetExists.
         *
         * @access  public
         * @param  mixed $name Data name.
         * @return bool
         * @throw   \Hoa\Session\Exception\Locked
         */
        public function __isset($name)
        {
            return $this->offsetExists($name);
        }

        /**
         * Get a data. Alias of offsetGet.
         *
         * @access  public
         * @param  mixed $name Data name.
         * @return mixed
         * @throw   \Hoa\Session\Exception\Locked
         */
        public function __get($name)
        {
            return $this->offsetGet($name);
        }

        /**
         * Set a data. Alias of offsetSet.
         *
         * @access  public
         * @param  mixed        $name  Data name.
         * @param  mixed        $value Data value.
         * @return \Hoa\Session
         * @throw   \Hoa\Session\Exception\Locked
         */
        public function __set($name, $value)
        {
            $this->offsetSet($name, $value);
        }

        /**
         * Unset a data. Alias of offsetUnset.
         *
         * @access  public
         * @param  mixed $offset Data name.
         * @return void
         * @throw   \Hoa\Session\Exception\Locked
         */
        public function __unset($name)
        {
            $this->offsetUnset($name);
        }
    }
}
