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
* Base Column Interface
*/
interface DataColumnInterface
{

    /**
     * @return boolean
     */
    public function getAllowNull();


    /**
     * @param boolean $allowNull
     */
    public function setAllowNull($allowNull);


    /**
     * @return mixed
     */
    public function getDefault();


    /**
     * @param mixed $default
     */
    public function setDefault($default);


    /**
     * @return int
     */
    public function getMaxLength();


    /**
     * @param int $maxLength
     */
    public function setMaxLength($maxLength);


    /**
     * @return boolean
     */
    public function getMultipleKey();


    /**
     * @param boolean $multipleKey
     */
    public function setMultipleKey($multipleKey);


    /**
     * @return string
     */
    public function getName();


    /**
     * @param string $name
     */
    public function setName($name);


    /**
     * @return boolean
     */
    public function getNumeric();


    /**
     * @param boolean $numeric
     */
    public function setNumeric($numeric);


    /**
     * @return boolean
     */
    public function getPrimaryKey();


    /**
     * @param boolean $primaryKey
     */
    public function setPrimaryKey($primaryKey);


    /**
     * @return string
     */
    public function getType();


    /**
     * @param string $type
     */
    public function setType($type);


    /**
     * @return boolean
     */
    public function getUniqueKey();


    /**
     * @param boolean $uniqueKey
     */
    public function setUniqueKey($uniqueKey);
}

?>
