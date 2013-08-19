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
 * Base abstract class for all components.
 */
abstract class AbstractComponent extends AbstractObject implements ProcessInterface
{

    /**
     * Initializes the class.
     *
     * @return bool
     */
    public function init()
    {
        return true;
    }


    /**
     * Opens the class for use.
     *
     * @return bool
     */
    public function open()
    {
        return true;
    }


    /**
     * Executes class processing.
     *
     * @return bool
     */
    public function process()
    {
        return true;
    }


    /**
     * Closes the class.
     *
     * @return bool
     */
    public function close()
    {
        return true;
    }
}
