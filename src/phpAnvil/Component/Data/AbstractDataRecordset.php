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
use phpAnvil\Component\Collection\CollectionInterface;
use phpAnvil\Component\Data\DataConnectionInterface;
use phpAnvil\Component\Data\DataRecordsetInterface;
use phpAnvil\Component\Data\DataTypeInterface;


/**
 * Data Recordset Abstract Class
 *
 */
abstract class AbstractDataRecordset extends AbstractProcessObject implements DataRecordsetInterface, DataTypeInterface
{

    /**
     * An array containing column data for the recordset.
     *
     * @var \phpAnvil\Component\Collection\CollectionInterface $columns
     */
    protected $columns;

    /**
     * Data Connection Object
     *
     * @var \phpAnvil\Component\Data\DataConnectionInterface $dataConnection
     */
    protected $dataConnection;



    /**
     * The query execution result set.
     *
     * @var mixed $result
     */
    protected $result;

    /**
     * Current row data array.
     *
     * @var array $rowData
     */
    protected $rowData;

    /**
     * Current row number.
     *
     * @var int $rowNumber
     */
    protected $rowNumber = 0;

    /**
     * SQL query string.
     *
     * @var string
     */
    protected $sql;


    /**
     * Constructor.
     *
     * @param \phpAnvil\Component\Data\DataConnectionInterface                    $dataConnection
     * @param null|string                                                         $sql
     * @param null                                                                $result
     */
    public function __construct(DataConnectionInterface $dataConnection, $sql = null, $result = null)
    {
        $this->dataConnection = $dataConnection;
        $this->sql = $sql;
        $this->result = $result;
    }


    //==== Property Get/Set Functions ==========================================


    /**
     * @return \phpAnvil\Component\Collection\CollectionInterface
     */
    public function getColumns()
    {
        return $this->columns;
    }


    /**
     * @return \phpAnvil\Component\Data\DataConnectionInterface
     */
    public function getDataConnection()
    {
        return $this->dataConnection;
    }


    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }


    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }


    /**
     * @return array
     */
    public function getRowData()
    {
        return $this->rowData;
    }


    /**
     * @return int
     */
    public function getRowNumber()
    {
        return $this->rowNumber;
    }


    /**
     * @return string
     */
    public function getSql()
    {
        return $this->sql;
    }


    /**
     * @param string $sql
     */
    public function setSql($sql)
    {
        $this->sql = $sql;
    }


    //==== Public Method Functions =============================================


    /**
     * Closes the recordset.
     */
    public function close()
    {
    }


    /**
     * Returns true if the column exists.
     *
     * @param $name
     *
     * @return bool
     */
    public function columnExists($name)
    {
        return array_key_exists($name, $this->rowData);
    }


    /**
     * Returns the number of data rows.
     *
     * @return int
     */
    public function count()
    {
        return 0;
    }


    /**
     * Returns data for a specific column with the option of type formatting.
     *
     * @param int|string $column
     * @param int $dataType
     *
     * @return mixed
     */
    public function getData($column, $dataType = 0)
    {
        $return = $this->rowData[$column];

        if ($dataType > 0) {
            $value = $return;
            $locale = $this->dataConnection->getLocale();

            switch ($dataType) {
                case self::DATA_TYPE_DATE:

                    if (!empty($value) && strtolower($value) != 'null') {
                        $dateTime = new \DateTime($value);

                        $return = $dateTime->format($locale->getDateFormat());
                    }
                    break;

                case self::DATA_TYPE_DTS:
                case self::DATA_TYPE_ADD_DTS:
                    if (!empty($value) && strtolower($value) != 'null') {
                        $dateTime = new \DateTime($value, new \DateTimeZone('UTC'));
                        if ($this->dataConnection->getLocale()->hasDateTimeZone()) {
                            $dateTime->setTimezone($this->dataConnection->getLocale()->getDateTimeZone());
                        }

                        $return = $dateTime->format($locale->getDTSFormat());
                    }
                    break;

                case self::DATA_TYPE_DATE_STRING:

                    if (!empty($value) && strtolower($value) != 'null') {
                        $return = date($locale->getDateFormat(), strtotime($value));
                    }
                    break;

                case self::DATA_TYPE_DTS_STRING:
                    if (!empty($value) && strtolower($value) != 'null') {
                        $return = date($locale->getDTSFormat(), strtotime($value));
                    }
                    break;

                case self::DATA_TYPE_PHONE:
                case self::DATA_TYPE_STRING:
                    $return = stripslashes($value);
                    break;

                case self::DATA_TYPE_BOOLEAN:
                case self::DATA_TYPE_DECIMAL:
                case self::DATA_TYPE_FLOAT:
                case self::DATA_TYPE_NUMBER:
                default:
                    $return = $value;
                    break;
            }
        }

        return $return;
    }


    /**
     * Returns the current data row array.
     *
     * @return array
     */
    public function getRowArray()
    {
        return $this->rowData;
    }


    /**
     * Returns true if the recordset contains any row data.
     *
     * @return bool
     */
    public function hasRows()
    {
        return $this->count() > 0;
    }


    /**
     * Moves to the first row.
     *
     * @return bool
     */
    public function moveFirst()
    {
        $this->rowNumber = 0;

        return true;
    }


    /**
     * Moves to the last row.
     *
     * @return bool
     */
    public function moveLast()
    {
        $totalRows = $this->count();
        if ($totalRows > 0) {
            $this->rowNumber = $totalRows - 1;
        }

        return true;
    }


    /**
     * Moves to a specific row number.
     *
     * @param int $rowNumber
     *
     * @return bool
     */
    public function moveToRow($rowNumber)
    {
        $this->rowNumber = $rowNumber;

        return true;
    }


    /**
     * @param int    $number
     * @param string $message
     * @param string $detail
     */
    public function processError($number, $message = '', $detail = '')
    {
        $detail = $this->sql . $detail;

        $error_message = '<b>MySQL Error [' . $number . '] ' . $message . "</b><br><br>\n";
        $error_message .= $detail . "<br><br>\n";

//        $this->_logDebug($this->_anvilDataConnection->errorCallback, 'errorCallback');

//        $this->logError('[' . $number . '] ' . $message, 'anvilData Error');
//        $this->logError($detail, 'anvilData Error Detail');

        $errorCallback = $this->dataConnection->getErrorCallback();
        if ($errorCallback) {
            call_user_func($errorCallback, $this->dataConnection, $this, $number, $message, $detail);
        } elseif ($this->dataConnection->getBreakOnError()) {
            trigger_error($error_message, E_USER_ERROR);
        }
    }


    /**
     * Reads the next data row.
     *
     * @return bool
     */
    public function read()
    {
        return true;
    }


    /**
     * Returns an array of data rows.
     *
     * @param array $rows
     *
     * @return array
     */
    public function toArray($rows = array())
    {
        if ($this->read()) {
            $totalColumns = $this->columns->count();

            do {
                $this->rowNumber++;

                for ($i = 0; $i < $totalColumns; $i++) {
                    array_push($rows, $this->getData($i));
                }
            } while ($this->read());
        }

        return $rows;
    }

}

?>
