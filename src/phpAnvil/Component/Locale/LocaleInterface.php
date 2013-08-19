<?php
/*
 * This file is part of the phpAnvil framework.
 *
 * (c) Nick Slevkoff <nick@phpanvil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace phpAnvil\Component\Locale;

/**
* Regional format settings.
*/
interface LocaleInterface
{
    /**
     * @return string
     */
    public function getDateFormat();


    /**
     * @param string $dateFormat
     */
    public function setDateFormat($dateFormat);


    /**
     * @return \DateTimeZone
     */
    public function getDateTimeZone();


    /**
     * @param $dateTimeZone
     */
    public function setDateTimeZone($dateTimeZone);


    /**
     * @return string
     */
    public function getDefaultTimezone();


    /**
     * @param string $defaultTimezone
     */
    public function setDefaultTimezone($defaultTimezone);


    /**
     * @return string
     */
    public function getDTSFormat();


    /**
     * @param string $dtsFormat
     */
    public function setDTSFormat($dtsFormat);


    //==== Public Method Functions =============================================

    /**
     * Returns true if the dateTimeZone has been set.
     *
     * @return bool
     */
    public function hasDateTimeZone();

    /**
     * Sets the current regional timezone.
     *
     * @param $timezone
     *
     * @return bool
     */
    public function setTimezone($timezone = '');


}

?>