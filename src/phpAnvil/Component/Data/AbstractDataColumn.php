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

use phpAnvil\Component\Data\DataColumnInterface;


/**
* Base Column Abstract Class
*/
abstract class AbstractDataColumn implements DataColumnInterface
{

    /**
     * If true, the column allows nulls.
     *
     * @var bool $allowNull
     */
    protected $allowNull = true;

    /**
     * Default value for the column.
     *
     * @var mixed $default
     */
    protected $default;

    /**
     * Mex character length.
     *
     * @var int $maxLength
     */
    protected $maxLength;

    /**
     * If true, the column is part of a multiple column primary key.
     *
     * @var bool $multipleKey
     */
    protected $multipleKey = false;

    /**
     * Column name.
     *
     * @var string $name
     */
    protected $name;

    /**
     * If true, the column contains a numeric value.
     *
     * @var bool $numeric
     */
    protected $numeric = false;

    /**
     * If true, the column is a primary key.
     *
     * @var bool $primaryKey
     */
    protected $primaryKey = false;

    /**
     * Data type for the column.
     *
     * @var string $type
     */
    protected $type;

    /**
     * If true, the column is a unique key.
     *
     * @var bool $uniqueKey
     */
    protected $uniqueKey = false;


	/**
	* construct
	*
	* @param $name
    *   A string containing the name of the column.
	* @param $type
    *   An integer indicating the data type for the column.
	*/
	public function __construct($name, $type = '')
	{
		$this->name = $name;
		$this->type = $type;
	}


    /**
     * @return boolean
     */
    public function getAllowNull()
    {
        return $this->allowNull;
    }


    /**
     * @param boolean $allowNull
     */
    public function setAllowNull($allowNull)
    {
        $this->allowNull = $allowNull;
    }


    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }


    /**
     * @param mixed $default
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }


    /**
     * @return int
     */
    public function getMaxLength()
    {
        return $this->maxLength;
    }


    /**
     * @param int $maxLength
     */
    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;
    }


    /**
     * @return boolean
     */
    public function getMultipleKey()
    {
        return $this->multipleKey;
    }


    /**
     * @param boolean $multipleKey
     */
    public function setMultipleKey($multipleKey)
    {
        $this->multipleKey = $multipleKey;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @return boolean
     */
    public function getNumeric()
    {
        return $this->numeric;
    }


    /**
     * @param boolean $numeric
     */
    public function setNumeric($numeric)
    {
        $this->numeric = $numeric;
    }


    /**
     * @return boolean
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }


    /**
     * @param boolean $primaryKey
     */
    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;
    }


    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


    /**
     * @return boolean
     */
    public function getUniqueKey()
    {
        return $this->uniqueKey;
    }


    /**
     * @param boolean $uniqueKey
     */
    public function setUniqueKey($uniqueKey)
    {
        $this->uniqueKey = $uniqueKey;
    }
}

?>
