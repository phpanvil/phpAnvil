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

use phpAnvil\Component\Object\AbstractObject;
use phpAnvil\Component\Dynamic\DynamicProperty;

/**
 * Represents a collection of DynamicProperty objects for AbstractDynamicObject.
 */
class DynamicProperties extends AbstractObject
{

    /**
     * Array of properties.
     *
     * @var array $properties
     */
    protected $properties = array();

    /**
     * Array of property indexes.
     *
     * @var array $propertyIndex
     */
    protected $propertyIndex = array();


    /**
     * Returns true, if the property exists.
     *
     * @param $propertyName
     *
     * @return bool
     */
    public function exists($propertyName)
    {
        if (is_numeric($propertyName))
        {
            $propertyName = $this->propertyIndex[$propertyName];
        } else {
        }

        $return = isset($this->properties[$propertyName]);

        return $return;
    }


    /**
     * Creates a new property.
     *
     * @param string $propertyName
     *
     * @return \phpAnvil\Component\Dynamic\DynamicProperty
     */
    protected function newProperty($propertyName = '')
    {
        return new DynamicProperty($propertyName);
    }


    /**
     * Returns a property.
     *
     * @param      $propertyName
     * @param bool $addIfNotExist
     *
     * @return \phpAnvil\Component\Dynamic\DynamicProperty
     */
    public function property($propertyName, $addIfNotExist = false)
    {
        $return = null;

        if (is_numeric($propertyName))
        {
            $propertyName = $this->propertyIndex[$propertyName];
        } else {
            $propertyName = strtolower($propertyName);
        }

        if (array_key_exists($propertyName, $this->properties))
        {
            $return = $this->properties[$propertyName];
        } else {
            if ($addIfNotExist)
            {
                $this->properties[$propertyName] = $this->newProperty($propertyName);
                $this->propertyIndex[] = $propertyName;

                $return = $this->properties[$propertyName];
            }
        }

        return $return;
    }


    /**
     * Returns a property.
     *
     * @param $propertyName
     *
     * @return \phpAnvil\Component\Dynamic\DynamicProperty
     */
    public function &__get($propertyName)
    {
        if (!array_key_exists($propertyName, $this->properties))
        {
            $this->properties[$propertyName] = $this->newProperty($propertyName);
            $this->propertyIndex[] = $propertyName;
        }
        $return = $this->properties[$propertyName];

        return $return;
    }


    /**
     * Returns true, if the property has been set.
     *
     * @param $propertyName
     *
     * @return bool
     */
    public function __isset($propertyName)
    {
        $return = isset($this->properties[$propertyName]);

        return $return;

    }


    /**
     * Resets all property values to the defaultValue or an empty string.
     */
    public function reset()
    {
        $count = $this->count();
        for ($i = 0; $i < $count; $i++)
        {
            if (isset($this->properties[$this->propertyIndex[$i]]->defaultValue)) {
                $this->properties[$this->propertyIndex[$i]]->value = $this->properties[$this->propertyIndex[$i]]->defaultValue;
            } else {
                $this->properties[$this->propertyIndex[$i]]->value = '';
            }
            $this->properties[$this->propertyIndex[$i]]->changed = false;
        }
    }


    /**
     * Resets the property changed flag to false for all properties.
     */
    public function resetChanged()
    {
        $count = $this->count();

        for ($i = 0; $i < $count; $i++)
        {
            $this->properties[$this->propertyIndex[$i]]->changed = false;
        }
    }


    /**
     * Returns an array containing all property objects.
     *
     * @return array
     */
    public function toArray()
    {
        $newArray = array();

        $count = $this->count();

        foreach($this->properties as $name => $object)
        {
            $newArray[$object->name] = $object->value;
        }

        return $newArray;
    }


    /**
     * Returns the number of properties.
     *
     * @return int
     */
    public function count()
    {
        return count($this->properties);
    }

}

?>
