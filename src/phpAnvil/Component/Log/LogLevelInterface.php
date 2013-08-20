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


/**
 * Log Level Constants
 */
interface LogLevelInterface
{
    /**
     * Disables all logging.
     */
    const LOG_LEVEL_DISABLED = 0;

    /**
     * Logs emergency errors only.
     */
    const LOG_LEVEL_EMERGENCY = 1;

    /**
     * Logs alert and emergency errors.
     */
    const LOG_LEVEL_ALERT = 2;

    /**
     * Logs critical, alert, and emergency errors.
     */
    const LOG_LEVEL_CRITICAL = 3;

    /**
     * Logs all errors.
     */
    const LOG_LEVEL_ERROR = 4;

    /**
     * Logs warnings and errors.
     */
    const LOG_LEVEL_WARNING = 5;

    /**
     * Logs info, warnings, and errors.
     */
    const LOG_LEVEL_INFO = 6;

    /**
     * Alias for LOG_LEVEL_INFO, for backward compatibility.
     */
    const LOG_LEVEL_BRIEF_INFO = 6;

    /**
     * Alias for LOG_LEVEL_INFO, for backward compatibility.
     */
    const LOG_LEVEL_VERBOSE_INFO = 6;

    /**
     * Logs debug, info, warnings, and errors.
     */
    const LOG_LEVEL_DEBUG = 7;

    /**
     * Logs everything.
     */
    const LOG_LEVEL_ALL = 99;

}
