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

/**
 * Adds Singleton functionality to classes.
 */
trait SingletonTrait
{

    private static $singletonInstance;


    public static function getInstance()
    {
        if (!(self::$singletonInstance instanceof self)) {
            self::$singletonInstance = new self;
        }

        return self::$singletonInstance;
    }

}
