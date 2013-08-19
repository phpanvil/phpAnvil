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

/**
 * Application Exception
 */
class ApplicationException extends \RuntimeException
{
    const OPEN_ERROR = 1;

    const PROCESS_ERROR = 2;

    const CLOSE_ERROR = 3;
}
