<?php
/*
 * This file is part of the phpAnvil framework.
 *
 * (c) Nick Slevkoff <nick@phpanvil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace phpAnvil\Component\Log;

use phpAnvil\Component\Log\LogLevelInterface;
use phpAnvil\Component\Log\LogRelayInterface;
use phpAnvil\Component\Router\AbstractRelay;

/**
 * Base abstract class for all log relays.
 */
abstract class AbstractLogRelay extends AbstractRelay implements LogLevelInterface, LogRelayInterface
{

    /**
     * @param $name
     *
     * @return bool
     */
    public function endGroup($name = '')
    {
        return true;
    }


    /**
     * @param        $detail
     * @param        $title
     * @param int    $logLevel
     * @param string $file
     * @param string $method
     * @param int    $line
     *
     * @return bool
     */
    public function process($detail, $title = '', $logLevel = self::LOG_LEVEL_DEBUG, $file = '', $method = '', $line = 0)
    {
        return true;
    }


    /**
     * Starts a group.
     *
     * @param string $name
     *
     * @param string $title
     *
     * @return bool
     */
    public function startGroup($name, $title = '')
    {
        return true;
    }
}
