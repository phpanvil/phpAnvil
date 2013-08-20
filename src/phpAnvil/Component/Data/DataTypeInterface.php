<?php
/*
 * This file is part of the phpAnvil framework.
 *
 * (c) Nick Slevkoff <nick@phpanvil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace phpAnvil\Component\Data;


/**
 * Data Type Constants
 */
interface DataTypeInterface
{
    /**
     * Ignore the data type.
     */
    const DATA_TYPE_IGNORE = 0;

    /**
     * Boolean (true/false)  A zero value is false, while all other values
     * are treated as true.
     */
    const DATA_TYPE_BOOLEAN = 1;

    //---- Date Data Types -------------------------------------------------

    /**
     * Date with timezone conversion.
     */
    const DATA_TYPE_DATE = 2;

    /**
     * Date and time with timezone conversion. Automatically set when saving a
     * new record.
     */
    const DATA_TYPE_ADD_DTS = 6;

    /**
     * Date and time with timezone conversion.
     */
    const DATA_TYPE_DTS = 3;

    /**
     * Time with timezone conversion.
     */
    const DATA_TYPE_TIME = 8;

    //---- Date Data Types Without Timezone Conversions ----------------------

    /**
     * Date as a string.
     */
    const DATA_TYPE_DATE_STRING = 14;

    /**
     * Date and time as a string.
     */
    const DATA_TYPE_DTS_STRING = 15;

    //---- Numeric Data Types --------------------------------------------------

    /**
     * Integer number.
     */
    const DATA_TYPE_INTEGER = 4;

    /**
     * Old integer number for backwards compatibility.
     */
    const DATA_TYPE_NUMBER = self::DATA_TYPE_INTEGER;

    /**
     * Old integer number for backwards compatibility.
     */
    const DATA_TYPE_NUMERIC = self::DATA_TYPE_INTEGER;


    /**
     * Float number.
     */
    const DATA_TYPE_FLOAT = 7;

    /**
     * Old float number for backwards compatibility.
     */
    const DATA_TYPE_DECIMAL = self::DATA_TYPE_FLOAT;


    //---- String Data Types ---------------------------------------------------

    /**
     * Alphanumeric string.
     */
    const DATA_TYPE_STRING = 5;

    /**
     * Email formatted string.
     */
    const DATA_TYPE_EMAIL = 9;

    /**
     * Phone formatted string.
     */
    const DATA_TYPE_PHONE = 10;

    /**
     * Credit card formatted string.
     */
    const DATA_TYPE_CREDITCARD = 11;

    /**
     * Social Security Number formatted string.
     */
    const DATA_TYPE_SSN = 12;

    /**
     * Array of data.
     */
    const DATA_TYPE_ARRAY = 13;

}
