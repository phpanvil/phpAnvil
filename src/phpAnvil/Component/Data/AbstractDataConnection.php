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

use phpAnvil\Component\Object\AbstractProcessObject;
use phpAnvil\Component\Data\DataConnectionInterface;
use phpAnvil\Component\Locale\LocaleInterface;

/**
 * Abstract Data Connection Class
 */
abstract class AbstractDataConnection extends AbstractProcessObject implements DataConnectionInterface
{
    /**
     * Breaks code execution on errors.
     *
     * @var bool $breakOnError
     */
    protected $breakOnError = true;

    /**
     * Actual Connection object to the Data.
     *
     * @var mixed $connection
     */
    protected $connection;

    /**
     * Name of the database to connect to.
     *
     * @var string $databaseName
     */
    protected $databaseName;

    /**
     * Date format applied to all date fields being saved.
     *
     * @var string $dateFormat
     */
    protected $dateFormat = 'Y-m-d';

    /**
     * Date and time format applied to all datetime fields being saved.
     * @var string $dtsFormat
     */
    protected $dtsFormat = 'Y-m-d H:i:s';

    /**
     * Callback function to execute when an error is detected.
     *
     * @var mixed $errorCallback
     */
    protected $errorCallback;

    /**
     * Error Messages
     *
     * @var array $errorMessages
     */
    protected $errorMessages = array();

    /**
     * Host IP/Name to the data server.
     *
     * @var string $host
     */
    protected $host = 'localhost';

    /**
     * Locale to use when formatting data for use.
     *
     * @var \phpAnvil\Component\Locale\LocaleInterface $locale
     */
    protected $locale;

    /**
     * User authentication password.
     *
     * @var string $password
     */
    protected $password;

    /**
     * If True, the data connection will be persistent.
     *
     * @var bool $persistent
     */
    protected $persistent = false;

    /**
     * Port number to the data server.
     *
     * @var int $port
     */
    protected $port;

    /**
     * Table Prefix
     *
     * @var string $tablePrefix
     */
    protected $tablePrefix;

    /**
     * Connection type.
     *
     * @var string $type
     */
    protected $type;

    /**
     * User authentication username.
     *
     * @var string $username
     */
    protected $username;


    /**
     * Constructor.
     *
     * @param \phpAnvil\Component\Locale\LocaleInterface $locale
     * @param string                                     $host
     * @param string                                     $databaseName
     * @param string                                     $username
     *   A string containing the username for the connection's security login.
     * @param string                                     $password
     *   A string containing the password for the connection's security login.
     * @param bool                                       $persistent
     *   (optional) Setting to TRUE will enable persistent connections. [FALSE]
     * @param string                                     $tablePrefix
     */
    public function __construct(LocaleInterface $locale, $host = '', $databaseName = '', $username = '', $password = '',
                                $persistent = false, $tablePrefix = '')
    {
        $this->setHost($host);
        $this->setDatabaseName($databaseName);
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setPersistent($persistent);
        $this->setTablePrefix($tablePrefix);

        $this->locale = $locale;
    }


    /**
     * @return \phpAnvil\Component\Locale\LocaleInterface
     */
    public function getLocale()
    {
        return $this->locale;
    }


    /**
     * @param \phpAnvil\Component\Locale\LocaleInterface $locale
     */
    public function setLocale(LocaleInterface $locale)
    {
        $this->locale = $locale;
    }


    /**
     * Returns true if there are any error messages.
     *
     * @return bool
     */
    public function hasError()
    {
        return count($this->errorMessages) > 0;
    }


    /**
     * Formats a boolean value for use in SQL.
     *
     * @param $value
     *
     * @return int
     */
    public function formatBool($value)
    {
        if ($value) {
            return 1;
        } else {
            return 0;
        }
    }


    /**
     * Formats a date value for use in SQL.
     *
     * @param $value
     *
     * @return string
     */
    public function formatDate($value)
    {
        $return = "'" . date($this->dateFormat, strtotime($value)) . "'";

        return $return;
    }


    /**
     * Formats a date and time value for use in SQL.
     *
     * @param $value
     *
     * @internal param string $format
     *
     * @return string
     */
    public function formatDTS($value)
    {
        $return = "null";

        if ($value) {
            $return = "'" . date($this->dtsFormat, strtotime($value)) . "'";
        }
        return $return;
    }


    /**
     * Formats a float value for use in SQL.
     *
     * @param $value
     *
     * @return float|string
     */
    public function formatFloat($value)
    {
        $return = "null";

        if ($value) {
            $return = floatval($value);
        }

        return $return;
    }


    /**
     * Formats an int value for use in SQL.
     *
     * @param $value
     *
     * @return int|string
     */
    public function formatInt($value)
    {
        $return = "null";

        if ($value) {
            $return = intval($value);
        }

        return $return;
    }


    /**
     * Formats a string value for use in SQL.
     *
     * @param $value
     *
     * @return string
     */
    public function formatString($value)
    {
        $return = "null";

        if ($value && $this->isConnected()) {
            $return = "'" . $value . "'";
        }

        return $return;
    }




    /**
     * @param string $sql
     *
     * @return \phpAnvil\Component\Data\DataRecordsetInterface|void
     */
    public function execute($sql)
    {}


    /**
     * Returns true if a connection is detected.
     *
     * @return bool
     */
    public function isConnected()
    {
        return isset($this->connection);
    }


    /**
     * @return boolean
     */
    public function getBreakOnError()
    {
        return $this->breakOnError;
    }


    /**
     * @param boolean $breakOnError
     */
    public function setBreakOnError($breakOnError)
    {
        $this->breakOnError = $breakOnError;
    }


    /**
     * @param bool $autoOpen
     *
     * @return mixed
     */
    public function getConnection($autoOpen = true)
    {
        if ($autoOpen && !$this->isConnected()) {
            $this->open();
        }

        return $this->connection;
    }


    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }


    /**
     * @param string $databaseName
     */
    public function setDatabaseName($databaseName)
    {
        $this->databaseName = $databaseName;
    }


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


    /**
     * @return mixed
     */
    public function getErrorCallback()
    {
        return $this->errorCallback;
    }


    /**
     * @param mixed $errorCallback
     */
    public function setErrorCallback($errorCallback)
    {
        $this->errorCallback = $errorCallback;
    }


    /**
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }


    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }


    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }


    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }


    /**
     * @return boolean
     */
    public function getPersistent()
    {
        return $this->persistent;
    }


    /**
     * @param boolean $persistent
     */
    public function setPersistent($persistent)
    {
        $this->persistent = $persistent;
    }


    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }


    /**
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }


    /**
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }


    /**
     * @param string $tablePrefix
     */
    public function setTablePrefix($tablePrefix)
    {
        $this->tablePrefix = $tablePrefix;
    }


    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }


    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }


    /**
     * Returns the ID number for the primary key field of the last record saved.
     *
     * @return int
     */
    public function getLastInsertID()
    {
        return false;
    }

}

?>
