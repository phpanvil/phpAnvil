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
use phpAnvil\Component\Dynamic\DynamicProperties;

/**
* Base Dynamic Object Class
*/
abstract class AbstractDynamicObject extends AbstractObject
{
//    public $autoAddOnSet = true;

    /**
     * @var \phpAnvil\Component\Dynamic\DynamicProperties $properties
     */
    public $properties;

    /**
     * Array of callbacks.
     *
     * @var array $callbacks
     */
    protected $callbacks = array();


    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->newProperties();
    }


    /**
     * Prepares the dynamic properties array for new properties.
     */
    protected function newProperties()
    {
        if (!isset($this->properties))
        {
            $this->properties = new DynamicProperties();
        }
    }


    /**
     * Returns the value of a dynamic property. Will automatically use a
     * "get" accessor function if it exists.
     *
     * @param $name
     *   A string containing the name of the property to get the value from.
     *
     * @throws \Exception
     * @exception string Invalid property!
     *
     * @return mixed Returns the value of the dynamic property or the assigned default
     */
	public function __get($name)
    {
        $return = null;

		#---- Validate Whether Property Exists
        $isValid = $this->properties->exists($name);

        if ($isValid)
        {
		    #---- Use Custom Function Override if Exists, Otherwise Return Value
		    if (method_exists($this, 'get' . $name))
            {
			    $return = call_user_func(array($this, 'get' . $name));
		    } else {

			    #---- If Value Doesn't Exist, Use Default
                $return = $this->properties->property($name)->value;

                if ($return === '')
                {
                    $return = $this->properties->property($name)->defaultValue;
                }
		    }
        } else {
            throw new \Exception('Invalid property "' . $name . '"!');
        }

		return $return;
	}


	/**
	* Sets the value of a dynamic property. Will automatically use a "set"
    * accessor function if it exists.
	*
	* @param $name
    *   A string containing the name of the property to set the value to.
	* @param $value
    *   The value to set the dynamic property to.
    *
     * @return bool|mixed
     * @exception string Invalid property!
	*/
	public function __set($name, $value)
    {
//        fb::log($this->properties, 'properties');

        #---- Validate Whether Property Exists
        $isValid = $this->properties->exists($name);

        if ($isValid)
        {
		    #---- Use Custom Function Override if Exists, Otherwise Set Value
		    if (method_exists($this, 'set' . $name))
            {
			    return call_user_func(array($this, 'set' . $name), $value);
		    } else {

			    if ($this->properties->property($name)->value != $value)
                {
                    $this->properties->property($name)->priorValue = $this->properties->property($name)->value;
				    $this->properties->property($name)->changed = true;
			    }

			    $this->properties->property($name)->value = $value;
		    }
        } else {
            //---- Property is automatically added if a new $name
            $property = $this->properties->property($name, true);
            $property->name = $name;
            $property->value = $value;

//            $this->addDebugLog($this->properties);
        }

        return true;
	}


    /**
     * Returns whether a dynamic property has its value set.
     *
     * @param $name
     *   A string containing the name of the property to check if a value
     *   is set.
     *
     * @throws \Exception
     * @return bool Returns TRUE if the property's value is set, otherwise FALSE.
     */
	public function __isset($name)
    {
		$return = $this->properties->exists($name);

		if ($return)
        {

			$value = $this->properties->property($name)->value;

            //---- Process for empty() PHP function
			if ($value == 0 && !is_null($value))
            {
				#---- return false so that empty() works correctly with 0 numbers.
			} else {
				$return = !empty($value);
			}
        } else {
            throw new \Exception('Invalid property "' . $name . '"!');
        }

		return $return;
	}


	/**
	* Adds a custom callback function to the object, which will be executed
    * later.
	*
	* @param $name
    *   A string containing the name of the callback function.
	* @param $function
    *   A reference to the callback function itself.
    *
    * @see anvilDynamicObjectAbstract::executeCallback
	*/
	protected function addCallback($name, $function) {
		$this->callbacks[$name] = $function;
	}


	/**
	* Resets the changed property status.
	*/
	public function resetChangedProperties() {
        $this->properties->resetChanged();
	}


    /**
     * Executes a dynamic callback previously added using the addCallback
     * method.
     *
     * @param        $name
     *   A string containing the name of the callback to execute.
     * @param string $parameters
     *   (optional) An array of paramters to use with the callback function.
     *
     * @return bool|mixed Returns the The callback's response or FALSE if unable to execute
     */
	protected function executeCallback($name, $parameters = '') {
		if (array_key_exists($name, $this->callbacks)) {
			return call_user_func($this->callbacks[$name], $parameters);
		} else {
			return false;
		}
	}


	/**
	* Imports an array of dynamic property values.
	*
	* @param $properties
    *   (optional) An array of dynamic property values to set.
	*/
	public function importProperties($properties = null)
    {

		if (is_array($properties))
        {
			foreach($properties as $name => $newValue)
            {
                if ($this->properties->exists($name))
                {
                    if ($this->properties->property($name)->value != $newValue)
                    {
                        $this->properties->property($name)->priorValue = $this->properties->property($name)->value;
                        $this->properties->property($name)->changed = true;
                    }

                    $this->properties->property($name)->value = $newValue;
                }
			}
		}
	}


    /**
     * Returns true, if the property exists.
     *
     * @param $name
     *
     * @return bool
     */
    public function isProperty($name)
    {
        return $this->properties->exists($name);
	}


	/**
	* Resets all of the property values to their defaults.
	*
	*/
	public function resetProperties()
    {
        $this->properties->reset();
	}


	/**
	* Returns values for all dynamic properties as an array.
	*
	* @return array Values for all dynamic properties.
	*/
	public function toArray()
    {
		return $this->properties->toArray();
	}

}

?>
