<?php
/*
 * This file is part of the phpAnvil framework.
 *
 * (c) Nick Slevkoff <nick@phpanvil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace phpAnvil\Component\Router;

use phpAnvil\Component\Object\ObjectInterface;
use phpAnvil\Component\Router\RelayInterface;

/**
 * Base abstract class for all router classes.
 */
interface RouterInterface extends ObjectInterface
{

    /**
     * Adds a relay to the router.
     *
     * @param RelayInterface $relay
     */
    public function addRelay(RelayInterface $relay);


    /**
     * Returns a relay for a particular index.
     *
     * @param int $index
     *
     * @return RelayInterface
     */
    public function getRelay($index);

}
