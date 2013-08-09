<?php

    /**
     * PHY\Variable
     * https://github.com/mullanaphy/variable
     *
     * LICENSE
     * DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
     * http://www.wtfpl.net/
     */

    namespace PHY\Variable;

    /**
     * Generic methods all variables should be able to do. Mostly for chaining
     * and converting to other objects.
     *
     * @package PHY\Variable\AVar
     * @category PHY\Variable
     * @license http://www.wtfpl.net/
     * @author John Mullanaphy <john@jo.mu>
     * @abstract
     */
    abstract class AVar
    {

        protected $types = [];
        protected $original = null;
        protected $current = null;
        protected $type = null;
        protected $chain = null;
        protected $chaining = false;

        /**
         * Set the default value.
         *
         * @param mixed
         * @return \PHY\Variable\AVar
         */
        public function set($value)
        {
            $this->validate($value);
            $this->original = $value;
            $this->current = $value;
            $this->chaining = false;
            $this->chain = null;
            return $this;
        }

        /**
         * Set the default value if it exists.
         *
         * @param mixed $value
         */
        public function __construct($value)
        {
            $this->set($value);
        }

        /**
         * Chain methods together.
         *
         * @param mixed $value
         * @return \PHY\Variable\AVar
         */
        public function chain($value = null)
        {
            if (null !== $value) {
                $this->validate($value);
                $this->set($value);
            }
            $this->chaining = true;
            $this->chain = $this->current;
            return $this;
        }

        /**
         * Complete a chain and return it's result.
         *
         * @return mixed
         */
        public function complete()
        {
            $chain = $this->chain;
            $this->chaining = false;
            $this->chain = null;
            return $chain;
        }

        /**
         * Update a value.
         *
         * @param mixed $value
         * @return \PHY\Variable\AVar
         */
        public function update($value)
        {
            $this->validate($value);
            if ($this->chaining) {
                $this->chain = $value;
            } else {
                $this->current = $value;
            }
            return $this;
        }

        /**
         * Get the current iteration's value.
         * @return mixed
         */
        public function get()
        {
            if ($this->chaining) {
                return $this->chain;
            } else {
                return $this->current;
            }
        }

        /**
         * Get the type of variable.
         *
         * @return string
         */
        public function getType()
        {
            return gettype($this->current);
        }

        /**
         * Get the original value.
         *
         * @return mixed
         */
        public function getOriginal()
        {
            return $this->original;
        }

        /**
         * See if this variable's value are different from the beginning.
         *
         * @return bool
         */
        public function isDifferent()
        {
            return $this->original !== $this->current;
        }

        /**
         * Reset the variable back to it's original value.
         *
         * @return \PHY\Variable\AVar
         */
        public function reset()
        {
            $this->current = $this->original;
            $this->chaining = false;
            $this->chain = null;
            return $this;
        }

        public function validate($value)
        {
            if (!in_array(gettype($value), $this->types)) {
                throw new Exception('Type of value "'.gettype($value)."' is not compatible with ".get_class($this));
            }
        }

        /**
         * Translate this item to a string.
         *
         * @return string
         */
        public function toString()
        {
            return (string)$this->get();
        }

        /**
         * Translate this item to an array.
         *
         * @return array
         */
        public function toArray()
        {
            return (array)$this->get();
        }

        /**
         * Translate this item to a stdClass.
         *
         * @return stdClass
         */
        public function toObject()
        {
            return (object)$this->get();
        }

        /**
         * Translate this item to a float.
         *
         * @return float
         */
        public function toFloat()
        {
            return (float)$this->get();
        }

        /**
         * Translate this item to an int.
         *
         * @return int
         */
        public function toInt()
        {
            return (int)$this->get();
        }

        /**
         * Translate this item to a bool.
         *
         * @return bool
         */
        public function toBool()
        {
            return (bool)$this->get();
        }

    }
