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

use phpAnvil\Component\Collection\CollectionInterface;

/**
* Recordset Interface
*/
interface DataRecordsetInterface
{
    //==== Property Get/Set Functions ==========================================


    /**
     * @return \phpAnvil\Component\Collection\CollectionInterface
     */
    public function getColumns();


    /**
     * @return \phpAnvil\Component\Data\DataConnectionInterface
     */
    public function getDataConnection();


    /**
     * @return mixed
     */
    public function getResult();


    /**
     * @param mixed $result
     */
    public function setResult($result);


    /**
     * @return array
     */
    public function getRowData();


    /**
     * @return int
     */
    public function getRowNumber();


    /**
     * @return string
     */
    public function getSQL();


    /**
     * @param string $sql
     */
    public function setSQL($sql);


    //==== Public Method Functions =============================================


    /**
     * Closes the recordset.
     */
    public function close();


    /**
     * Returns true if the column exists.
     *
     * @param $name
     *
     * @return bool
     */
    public function columnExists($name);


    /**
     * Returns the number of data rows.
     *
     * @return int
     */
    public function count();


    /**
     * Returns data for a specific column with the option of type formatting.
     *
     * @param int|string $column
     * @param int        $dataType
     *
     * @return mixed
     */
    public function getData($column, $dataType = 0);


    /**
     * Returns the current data row array.
     *
     * @return array
     */
    public function getRowArray();


    /**
     * Returns true if the recordset contains any row data.
     *
     * @return bool
     */
    public function hasRows();


    /**
     * Moves to the first row.
     *
     * @return bool
     */
    public function moveFirst();


    /**
     * Moves to the last row.
     *
     * @return bool
     */
    public function moveLast();


    /**
     * Moves to a specific row number.
     *
     * @param int $rowNumber
     *
     * @return bool
     */
    public function moveToRow($rowNumber);


    /**
     * @param int    $number
     * @param string $message
     * @param string $detail
     */
    public function processError($number, $message = '', $detail = '');


    /**
     * Reads the next data row.
     *
     * @return bool
     */
    public function read();


    /**
     * Returns an array of data rows.
     *
     * @param array $rows
     *
     * @return array
     */
    public function toArray($rows = array());
}

?>
