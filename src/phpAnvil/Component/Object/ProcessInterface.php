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
 * Interface providing init, open, and close function skeleton.
 */
interface ProcessInterface extends ObjectInterface
{

    public function open();

    public function process();

    public function close();
}
