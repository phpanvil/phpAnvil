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
use phpAnvil\Component\Log\LogRouterInterface;
use phpAnvil\Component\Router\AbstractRouter;

/**
 * Log router class for the %phpAnvil %Framework.
 */
abstract class AbstractLogRouter extends AbstractRouter implements LogLevelInterface, LogRouterInterface
{

    /**
     * Temporary override the current log level.
     *
     * @var int $logLevelOverride
     */
    protected static $logLevelOverride = -1;

    /**
     * Current log level.
     *
     * @var int $logLevel
     */
    public $logLevel = self::LOG_LEVEL_ERROR;

    /**
     * If true, execution times will be included in the log.
     *
     * @var bool $logTime
     */
    protected $logTime = true;


    /**
     * Returns the set LogRouter.
     *
     * @return \phpAnvil\Component\Log\LogRouterInterface
     */
    public function getLog()
    {
        return $this;
    }


    /**
     * Sets the LogRouter to the object.
     *
     * @param \phpAnvil\Component\Log\LogRouterInterface $log
     */
    public function setLog(LogRouterInterface $log)
    {
    }


    /**
     * Logs an alert entry.
     *
     * @param string $detail
     * @param string $title
     */
    public function alert($detail, $title = '')
    {
        $this->process($detail, $title, self::LOG_LEVEL_ALERT);
    }


    /**
     * Logs a critical entry.
     *
     * @param string $detail
     * @param string $title
     */
    public function critical($detail, $title = '')
    {
        $this->process($detail, $title, self::LOG_LEVEL_CRITICAL);
    }


    /**
     * Logs a debug entry.
     *
     * @param string $detail
     * @param string $title
     */
    public function debug($detail, $title = '')
    {
        $this->process($detail, $title, self::LOG_LEVEL_DEBUG);
    }


    /**
     * Disables logging by setting the log level to @link{LOG_LEVEL_DISABLED}.
     */
    public function disable()
    {
        $this->logLevel = self::LOG_LEVEL_DISABLED;
    }


    /**
     * Logs an emergency entry.
     *
     * @param string $detail
     * @param string $title
     */
    public function emergency($detail, $title = '')
    {
        $this->process($detail, $title, self::LOG_LEVEL_EMERGENCY);
    }


    /**
     * Enables logging.
     */
    public function enable($logLevel = self::LOG_LEVEL_ALL)
    {
        $this->logLevel = $logLevel;
    }


    /**
     * @param $name
     */
    public function endGroup($name = '')
    {
        $maxRelays = count($this->relays);
        for ($i = 0; $i < $maxRelays; $i++) {
            /** @var LogRelayInterface $relay */
            $relay = $this->getRelay($i);
            $relay->endGroup($name);
        }
    }


    /**
     * Logs an error entry.
     *
     * @param string $detail
     * @param string $title
     */
    public function error($detail, $title = '')
    {
        $this->process($detail, $title, self::LOG_LEVEL_ERROR);
    }


    /**
     * Logs an info entry.
     *
     * @param string $detail
     * @param string $title
     */
    public function info($detail, $title = '')
    {
        $this->process($detail, $title, self::LOG_LEVEL_INFO);
    }


    /**
     * Returns true if logging is currently enabled.
     *
     * @param int $logLevel
     *
     * @return bool
     */
    public function isEnabled($logLevel = self::LOG_LEVEL_DEBUG)
    {
        return $this->logLevel >= $logLevel;
    }


    /**
     * Returns true if logging is enabled for the passed log level.
     *
     * @param int $logLevel
     *
     * @return bool
     */
    public function isLogging($logLevel = self::LOG_LEVEL_DEBUG)
    {
        return $this->isEnabled($logLevel);
    }


    /**
     * @param string $detail
     * @param string $title
     * @param int    $logLevel
     *
     * @param string $file
     * @param string $method
     * @param int    $line
     *
     * @return bool
     */
    public function process($detail, $title = '', $logLevel = self::LOG_LEVEL_DEBUG, $file = '', $method = '', $line = 0)
    {
        $backTrace = debug_backtrace(0);

        $index = 0;

//        while (isset($backTrace[$index]['class']) && 'phpAnvil\Component\Log' == substr($backTrace[$index]['class'], 0, 22))
        while (isset($backTrace[$index]['class']) && false !== stripos($backTrace[$index]['class'], 'phpAnvil\Component\Log')) {
            $index++;
        }

        $file = $backTrace[$index]['file'];
        $line = $backTrace[$index]['line'];
        $method = $backTrace[$index + 1]['class'] . '::' . $backTrace[$index + 1]['function'];

        $maxRelays = count($this->relays);
        for ($i = 0; $i < $maxRelays; $i++) {

            /** @var LogRelayInterface $relay */
            $relay = $this->getRelay($i);
            $relay->process($detail, $title, $logLevel, $file, $method, $line);

//            $relay->process($backTrace, $title, $logLevel, $file, $method, $line);
//            $relay->process($file, $title, $logLevel, $file, $method, $line);
//            $relay->process($line, $title, $logLevel, $file, $method, $line);
//            $relay->process($method, $title, $logLevel, $file, $method, $line);
        }

        return true;
    }


    /**
     * @param string $name
     * @param string $title
     */
    public function startGroup($name, $title = '')
    {
        $maxRelays = count($this->relays);
        for ($i = 0; $i < $maxRelays; $i++) {
            /** @var LogRelayInterface $relay */
            $relay = $this->getRelay($i);
            $relay->startGroup($name, $title);
        }
    }


    /**
     * Logs a warning entry.
     *
     * @param string $detail
     * @param string $title
     */
    public function warning($detail, $title = '')
    {
        $this->process($detail, $title, self::LOG_LEVEL_WARNING);
    }

}
