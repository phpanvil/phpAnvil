<?php
/*
 * This file is part of the phpAnvil framework.
 *
 * (c) Nick Slevkoff <nick@phpanvil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace phpAnvil\Component\Collection;

use phpAnvil\Component\Object\AbstractObject;
use phpAnvil\Component\Collection\CollectionInterface;


/**
 * Collection Class
 */
class Collection extends AbstractObject implements CollectionInterface
{


    /**
     * Array containing all items in the collection.
     *
     * @var array $items
     */
    protected $items = array();


    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }


    /**
     * @param array $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }


    /**
     * Adds an item into the collection.
     *
     * @param mixed $item
     * @param null $key
     */
    public function add($item, $key = null)
    {
        if ($key) {
            if (isset($this->items[$key])) {
//                $this->logWarning($key, 'Duplicate Key');
            } else {
                $this->items[$key] = $item;
            }
        } else {
            $this->items[] = $item;
        }
    }


    /**
     * Removes an item from the collection.
     *
     * @param string $key
     */
    public function remove($key)
    {
        if ($this->exists($key)) {
            unset($this->items[$key]);
        }
    }


    /**
     * Returns true if the item exists in the collection.
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists($key)
    {
//        return isset($this->items[$key]);
        return array_key_exists($key, $this->items);
    }


    /**
     * Returns an array of keys for the items in the collection.
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->items);
    }


    /**
     * Moves the index pointer to the first item in the collection.
     *
     * @return mixed
     */
    public function moveFirst()
    {
        return reset($this->items);
    }


    /**
     * Moves the index pointer to the next item in the collection.
     *
     * @return mixed The next item in the array, or false.
     */
    public function moveNext()
    {
        return next($this->items);
    }


    /**
     * Moves the index pointer to the previous item in the collection.
     *
     * @return bool True if successful, otherwise false if there are no previous items in the collection.
     */
    public function movePrev()
    {
        return prev($this->items);
    }


    /**
     * Moves the index pointer to the last item in the collection.
     *
     * @return mixed
     */
    public function moveLast()
    {
        return end($this->items);
    }


    /**
     * Determine if there are more items in the collection after the current index.
     *
     * @return boolean True if there are more items after the current indexed item in the collection.
     */
    public function hasMore()
    {
        return current($this->items);
    }


    /**
     * Returns the currently indexed item.
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->items);
    }


    #==========================================
    #====  ArrayAccessInterface Functions =====

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }


    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }


    /**
     * @param mixed $offset
     *
     * @return null
     */
    public function offsetGet($offset)
    {
        return isset($this->items[$offset])
                ? $this->items[$offset]
                : null;
    }


    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }


    #==========================================
    #==== IteratorAggregate Interface Functions =====

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }


    #==========================================
    #==== Countable Interface Functions =====

    /**
     * Returns the number of items within the collection.
     *
     * @return int Total number of items within the collection.
     */
    public function count()
    {
        return count($this->items);
    }



}

?>
