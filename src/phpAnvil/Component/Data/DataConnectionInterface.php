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

use phpAnvil\Component\Object\ProcessInterface;
use phpAnvil\Component\Locale\LocaleInterface;

/**
* Data Connection Interface
*/
interface DataConnectionInterface extends ProcessInterface
{

    /**
     * @return boolean
     */
    public function getBreakOnError();


    /**
     * @param boolean $breakOnError
     */
    public function setBreakOnError($breakOnError);


    /**
     * @param bool $autoOpen
     *
     * @return object
     */
    public function getConnection($autoOpen = true);


    /**
     * @return string
     */
    public function getDatabaseName();


    /**
     * @param string $databaseName
     */
    public function setDatabaseName($databaseName);


    /**
     * @return string
     */
    public function getDateFormat();


    /**
     * @param string $dateFormat
     */
    public function setDateFormat($dateFormat);


    /**
     * @return string
     */
    public function getDTSFormat();


    /**
     * @param string $dtsFormat
     */
    public function setDTSFormat($dtsFormat);


    /**
     * @return mixed
     */
    public function getErrorCallback();


    /**
     * @param mixed $errorCallback
     */
    public function setErrorCallback($errorCallback);


    /**
     * @return array
     */
    public function getErrorMessages();


    /**
     * @return string
     */
    public function getHost();


    /**
     * @param string $host
     */
    public function setHost($host);


    /**
     * @return \phpAnvil\Component\Locale\LocaleInterface
     */
    public function getLocale();


    /**
     * @param \phpAnvil\Component\Locale\LocaleInterface $locale
     */
    public function setLocale(LocaleInterface $locale);


    /**
     * @return string
     */
    public function getPassword();


    /**
     * @param string $password
     */
    public function setPassword($password);


    /**
     * @return boolean
     */
    public function getPersistent();


    /**
     * @param boolean $persistent
     */
    public function setPersistent($persistent);


    /**
     * @return int
     */
    public function getPort();


    /**
     * @param int $port
     */
    public function setPort($port);


    /**
     * @return string
     */
    public function getTablePrefix();


    /**
     * @param string $tablePrefix
     */
    public function setTablePrefix($tablePrefix);


    /**
     * @return string
     */
    public function getType();


    /**
     * @return string
     */
    public function getUsername();


    /**
     * @param string $username
     */
    public function setUsername($username);


    /**
     * Executes a SQL query.
     *
     * @param string $sql
     *
     * @return \phpAnvil\Component\Data\DataRecordsetInterface
     */
    public function execute($sql);


    /**
     * Formats a boolean value for use in SQL.
     *
     * @param $value
     *
     * @return int
     */
    public function formatBool($value);


    /**
     * Formats a date value for use in SQL.
     *
     * @param $value
     *
     * @return string
     */
    public function formatDate($value);


    /**
     * Formats a date and time value for use in SQL.
     *
     * @param $value
     *
     * @internal param string $format
     *
     * @return string
     */
    public function formatDTS($value);


    /**
     * Formats a float value for use in SQL.
     *
     * @param $value
     *
     * @return float|string
     */
    public function formatFloat($value);


    /**
     * Formats an int value for use in SQL.
     *
     * @param $value
     *
     * @return int|string
     */
    public function formatInt($value);


    /**
     * Formats a string value for use in SQL.
     *
     * @param $value
     *
     * @return string
     */
    public function formatString($value);

    /**
     * Returns the ID number for the primary key field of the last record saved.
     *
     * @return int
     */
    public function getLastInsertID();

}

?>