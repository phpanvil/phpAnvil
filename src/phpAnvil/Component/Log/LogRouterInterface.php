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
use phpAnvil\Component\Router\RouterInterface;

/**
 * Log Router Interface
 */
interface LogRouterInterface extends RouterInterface
{

    /**
     * Logs an alert entry.
     *
     * @param string $detail
     * @param string $title
     */
    public function alert($detail, $title = '');


    /**
     * Logs an info entry.
     *
     * @param string $detail
     * @param string $title
     */
    public function critical($detail, $title = '');


    /**
     * Logs a debug entry.
     *
     * @param string $detail
     * @param string $title
     */
    public function debug($detail, $title = '');


    /**
     * Disables logging by setting the log level to @link{LOG_LEVEL_DISABLED}.
     */
    public function disable();


    /**
     * Logs an emergency entry.
     *
     * @param string $detail
     * @param string $title
     */
    public function emergency($detail, $title = '');


    /**
     * Enables logging.
     */
    public function enable($logLevel = LogLevelInterface::LOG_LEVEL_ALL);


    /**
     * @param string $name
     */
    public function endGroup($name = '');


    /**
     * Logs an error entry.
     *
     * @param string $detail
     * @param string $title
     */
    public function error($detail, $title = '');


    /**
     * Logs an info entry.
     *
     * @param string $detail
     * @param string $title
     */
    public function info($detail, $title = '');


    /**
     * Returns true if logging is currently enabled.
     *
     * @param int $logLevel
     *
     * @return bool
     */
    public function isEnabled($logLevel = LogLevelInterface::LOG_LEVEL_DEBUG);


    /**
     * @param string $detail
     * @param string $title
     * @param int    $logLevel
     * @param string $file
     * @param string $method
     * @param int    $line
     *
     * @return bool
     */
    public function process($detail, $title = '', $logLevel = LogLevelInterface::LOG_LEVEL_DEBUG, $file = '', $method = '', $line = 0);


    /**
     * @param        $name
     * @param string $title
     *
     * @return
     */
    public function startGroup($name, $title='');


    /**
     * Logs a warning entry.
     *
     * @param string $detail
     * @param string $title
     */
    public function warning($detail, $title = '');

}
