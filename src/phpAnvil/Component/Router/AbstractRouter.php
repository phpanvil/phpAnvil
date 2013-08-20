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

use phpAnvil\Component\Object\AbstractObject;
use phpAnvil\Component\Router\RelayInterface;
use phpAnvil\Component\Router\RouterInterface;

/**
 * Base abstract class for all router classes.
 */
abstract class AbstractRouter extends AbstractObject implements RouterInterface
{
    /**
     * Contains the relays for the router.
     *
     * @var array $relays
     */
    protected $relays = array();


    /**
     * Adds a relay to the router.
     *
     * @param RelayInterface $relay
     */
    public function addRelay(RelayInterface $relay)
    {
        $this->relays[] = $relay;
    }


    /**
     * Returns a relay for a particular index.
     *
     * @param int $index
     *
     * @return RelayInterface
     */
    public function getRelay($index)
    {
        return $this->relays[$index];
    }

}
