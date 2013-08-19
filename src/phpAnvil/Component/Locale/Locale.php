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

use phpAnvil\Component\Object\AbstractObject;
use phpAnvil\Component\Locale\LocaleInterface;

/**
* Regional format settings.
*/
class Locale extends AbstractObject implements LocaleInterface
{
    /**
     * Date format.
     *
     * @var string $dateFormat
     */
    protected $dateFormat = 'm/d/Y';

    /**
     * Current DateTimeZone object.
     */
    protected $dateTimeZone;

    /**
     * Default Timezone.
     *
     * @var string $defaultTimezone
     */
    protected $defaultTimezone = 'UTC';

    /**
     * Date and time format.
     *
     * @var string $dtsFormat
     */
    protected $dtsFormat = 'm/d/Y h:i:s A';


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->dateTimeZone = new \DateTimeZone($this->defaultTimezone);
	}


    //==== Property Get/Set Functions ==========================================

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }


    /**
     * @param string $dateFormat
     */
    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }


    /**
     * @return \DateTimeZone
     */
    public function getDateTimeZone()
    {
        return $this->dateTimeZone;
    }


    /**
     * @param $dateTimeZone
     */
    public function setDateTimeZone($dateTimeZone)
    {
        $this->dateTimeZone = $dateTimeZone;
    }


    /**
     * @return string
     */
    public function getDefaultTimezone()
    {
        return $this->defaultTimezone;
    }


    /**
     * @param string $defaultTimezone
     */
    public function setDefaultTimezone($defaultTimezone)
    {
        $this->defaultTimezone = $defaultTimezone;
    }


    /**
     * @return string
     */
    public function getDTSFormat()
    {
        return $this->dtsFormat;
    }


    /**
     * @param string $dtsFormat
     */
    public function setDTSFormat($dtsFormat)
    {
        $this->dtsFormat = $dtsFormat;
    }


    //==== Public Method Functions =============================================

    /**
     * Returns true if the dateTimeZone has been set.
     *
     * @return bool
     */
    public function hasDateTimeZone()
    {
        return isset($this->dateTimeZone);
    }

    /**
     * Sets the current regional timezone.
     *
     * @param $timezone
     *
     * @return bool
     */
    public function setTimezone($timezone = '')
    {
        if (empty($timezone)) {
//            $this->logInfo('Timezone not set, using default (' . $this->defaultTimezone . '...');
            $timezone = $this->defaultTimezone;
        }

//        $this->logInfo('Setting regional timezone to ' . $timezone . '...');

        $this->dateTimeZone = new \DateTimeZone($timezone);

        return true;
    }



}

?>