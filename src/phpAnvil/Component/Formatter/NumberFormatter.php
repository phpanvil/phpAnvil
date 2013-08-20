<?php
/*
 * This file is part of the phpAnvil framework.
 *
 * (c) Nick Slevkoff <nick@phpanvil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace phpAnvil\Component\Formatter;


/**
 * Number Formatter
 */
class NumberFormatter
{
    /**
     * @var $number
     */
    protected $number;


    /**
     * Constructor
     *
     * @param float|int $number
     */
    public function __construct($number)
    {
        $this->setNumber($number);
    }


    /**
     * @return float|int
     */
    public function getNumber()
    {
        return $this->number;
    }


    /**
     * @param  float|int $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }


    /**
     * Returns the number of bytes in a formatted string.
     *
     * @param int $precision
     *
     * @return string
     */
    public function getBytes($precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($this->number, 0);

        $i = intval(floor(log($bytes, 1024)));

        $return = @round($bytes / pow(1024, ($i)), 2) . ' ' . $units[$i];

        return $return;
    }
}
