<?php
//require_once('anvilDynamicObject.abstract.php');
require_once PHPANVIL2_COMPONENT_PATH . 'anvilCollection.class.php';
require_once 'anvilDataRecordset.abstract.php';
require_once 'anvilDataRecordset.interface.php';
require_once 'anvilData_mysqli_Column.class.php';


/**
* MySQL Recordset
*
 * @property mysqli_result $result
*/
class anvilData_mysqli_Recordset extends anvilDataRecordsetAbstract
	implements anvilDataRecordsetInterface
{
	const VERSION	= '2.0';
	const ENGINE 	= 'mysqli';

    /**
     * @var mysqli $_connection
     */
    private $_connection;

	/**
	* construct
	*
	* @param $sql
    *   A string containing the SQL query used for this recordset.
	* @param $result
    *
	*/
	public function __construct($sql = null, mysqli_result $result = null, anvilDataConnectionAbstract $anvilDataConnection = null)
	{
        parent::__construct($sql, $result, $anvilDataConnection);

        $this->_connection = $anvilDataConnection->_connection;

		if ($this->_connection->error) {
            $this->processError($this->_connection->errno, $this->_connection->error);

		}
	}


	/**
	* Dynamic Get Function Override
	*
	* @param $name
    *   A string containing the name of the property to get.
	* @return
    *   Value of the property.
	*/
	public function __get($propertyName)
	{
//        global $firePHP;

		if ($propertyName == 'columns') {
			if (!isset($this->_columns)) {
				//---- Get Columns
				$this->_columns = new anvilCollection();

                if ($this->result->field_count > 0) {
                    while ($field = $this->result->fetch_field()) {

                            $newColumn = new anvilData_mysqli_Column($field->name, $field->type);

                            $this->_columns->add($newColumn);
                    }
                }
			}
			return $this->_columns;

		} else {
			return parent::__get($propertyName);
		}
	}


	public function close() {
        parent::close();

		$this->result->free();
	}


	public function count() {
		return $this->result->num_rows;
	}


	public function read() {
        $return = false;

        if ($this->result && $this->count() > 0) {
			if ($this->_row = $this->result->fetch_array()) {
				$this->rowNumber++;
				$return = true;
			}
		}

        return $return;
	}


	public function moveFirst() {
//		$this->rowNumber = 0;
//		return $this->result->field_seek(0);
        $return = $this->moveToRow(0);
        return $return;
    }


	public function moveLast() {
		$return = false;
		$totalRows = $this->count();
		if ($totalRows > 0) {
//            $this->rowNumber = $totalRows - 1;
//            $return = $this->result->field_seek($totalRows - 1);
            $return = $this->moveToRow($totalRows - 1);
		}
		return $return;
	}


	public function moveToRow($rowNumber) {
		$this->rowNumber = $rowNumber;
		return $this->result->field_seek($rowNumber);
	}

}

?>
