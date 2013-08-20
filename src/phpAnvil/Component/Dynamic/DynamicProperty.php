<?php
/*
 * This file is part of the phpAnvil framework.
 *
 * (c) Nick Slevkoff <nick@phpanvil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace phpAnvil\Component\Dynamic;

/**
 * Represents a single property in AbstractDynamicObject.
 */
class DynamicProperty
{
    /**
     * Property name.
     *
     * @var string $name
     */
    public $name;

    /**
     * Default property value.
     *
     * @var null|mixed $defaultValue
     */
    public $defaultValue = null;

    /**
     * If true, the property has been changed from its original value.
     *
     * @var bool $changed
     */
    public $changed = false;

    /**
     * Prior value of the property before it was changed.
     *
     * @var mixed $priorValue
     */
    public $priorValue;

    /**
     * Current property value.
     *
     * @var mixed $currentValue
     */
    protected $currentValue;


    /**
     * Constructor.
     *
     * @param string $name
     */
    public function __construct($name = '')
    {
        $this->name = $name;
    }


    /**
     * Returns the current property value.
     *
     * @param $name
     *
     * @return null|mixed
     */
    public function __get($name)
    {
        $return = null;

        if ($name == 'value') {
            $return = $this->currentValue;
        }

        return $return;
    }


    /**
     * Returns true if a property value has been set.
     *
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        $return = false;
        
        if ($name == 'value') {
            $value = $this->currentValue;

            //---- Process for empty() PHP function
            if ($value === 0 && !is_null($value))
            {
                #---- return false so that empty() works correctly with 0 numbers.
            } else {
                $return = $value != '';
            }

        } else {
//            $return = parent::__isset($name);
        }
        
        return $return;
    }


    /**
     * Sets the property value.
     *
     * @param $name
     * @param $value
     *
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        if ($name == 'value') {
            if ($this->currentValue != $value)
            {
                $this->priorValue = $this->currentValue;
                $this->changed = true;
            }

            $this->currentValue = $value;
        } else {
            throw new \Exception('Invalid property "' . $name . '"!');
        }
    }


    /**
     * Returns the current property value as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return strval($this->currentValue);
    }
}



?>
