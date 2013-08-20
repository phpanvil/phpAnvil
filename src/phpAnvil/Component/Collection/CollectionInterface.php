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

/**
 * Collection Interface
 */
interface CollectionInterface extends \ArrayAccess, \Countable, \IteratorAggregate
{

    /**
     * @return array
     */
    public function getItems();

    /**
     * @param array $items
     */
    public function setItems($items);


    /**
     * Adds an item into the collection.
     *
     * @param mixed $item
     * @param null $key
     */
    public function add($item, $key = null);


    /**
     * Removes an item from the collection.
     *
     * @param string $key
     */
    public function remove($key);


    /**
     * Returns true if the item exists in the collection.
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists($key);


    /**
     * Returns an array of keys for the items in the collection.
     *
     * @return array
     */
    public function keys();


    /**
     * Moves the index pointer to the first item in the collection.
     *
     * @return mixed
     */
    public function moveFirst();


    /**
     * Moves the index pointer to the next item in the collection.
     *
     * @return mixed The next item in the array, or false.
     */
    public function moveNext();


    /**
     * Moves the index pointer to the previous item in the collection.
     *
     * @return bool True if successful, otherwise false if there are no previous items in the collection.
     */
    public function movePrev();


    /**
     * Moves the index pointer to the last item in the collection.
     *
     * @return mixed
     */
    public function moveLast();


    /**
     * Determine if there are more items in the collection after the current index.
     *
     * @return boolean True if there are more items after the current indexed item in the collection.
     */
    public function hasMore();


    /**
     * Returns the currently indexed item.
     *
     * @return mixed
     */
    public function current();

}

?>
