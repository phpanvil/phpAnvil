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

use phpAnvil\Component\Object\AbstractObject;
use phpAnvil\Component\Object\ProcessInterface;

/**
 * Base abstract class for all processing objects.
 */
abstract class AbstractProcessObject extends AbstractObject implements ProcessInterface
{

    /**
     * Opens the object.
     */
    public function open()
    {
        return true;
    }


    /**
     * Primary processing function.
     */
    public function process()
    {
        return true;
    }


    /**
     * Closes the object.
     */
    public function close()
    {
        return true;
    }


}
