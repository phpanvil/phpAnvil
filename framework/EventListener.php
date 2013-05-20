<?php
namespace phpAnvil\Framework;

/**
 * This file is part of the phpAnvil framework.
 *
 * (c) Nick Slevkoff <nick@phpanvil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Simple class used for the event system.
 */
class EventListener {
    /**
     * @var string
     * Name of the event.
     */
    public $event = '';

    /**
     * @var string
     * Function callback to execute when the event is triggered.
     */
    public $callback = '';


    /**
     * Constructor.
     *
     * @param string $event
     * @param string|array $callback
     */
    function __construct($event, $callback)
    {
        $this->event = $event;
        $this->callback = $callback;
    }
}

?>
