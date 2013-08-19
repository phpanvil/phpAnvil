<?php
/*
 * This file is part of the phpAnvil framework.
 *
 * (c) Nick Slevkoff <nick@phpanvil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace phpAnvil\Component\Application;

use phpAnvil\Component\Object\ProcessInterface;

/**
 * Interface definition for the base Application class.
 */
interface ApplicationInterface extends ProcessInterface
{

    /**
     * Executes the application.
     */
    public function execute();

}
