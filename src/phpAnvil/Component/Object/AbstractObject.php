<?php
/*
 * This file is part of the phpAnvil framework.
 *
 * (c) Nick Slevkoff <nick@phpanvil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace phpAnvil\Component\Object;

use phpAnvil\Component\Object\ObjectInterface;


/**
 * Base abstract class for all %phpAnvil classes.
 */
abstract class AbstractObject implements ObjectInterface
{

    /**
     * Constructor.
     *
     * @param array $properties
     */
    public function __construct(array $properties = null)
    {
    }


    /**
     * Destructor to help force memory clearing.
     */
    public function __destruct()
    {
        foreach ($this as $index => $value) {
            unset($this->$index);
        }
    }

}
