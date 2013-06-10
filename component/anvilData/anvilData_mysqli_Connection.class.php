<?php
/**
 * @file
 * @author         Nick Slevkoff <nick@slevkoff.com>
 * @copyright      Copyright (c) 2010 Nick Slevkoff (http://www.slevkoff.com)
 * @license
 *                 This source file is subject to the new BSD license that is
 *                 bundled with this package in the file LICENSE.txt. It is also
 *                 available on the Internet at:  http://www.phpanvil.com/LICENSE.txt
 * @ingroup        phpAnvilTools anvilData anvilData_MySQL
 */

require_once('anvilDataConnection.abstract.php');

/**
 * MySQLi Data Connection
 *
 * @property MySQLi $_connection
 */
class anvilData_mysqli_Connection extends anvilDataConnectionAbstract
{
    const VERSION = '2.0';

    const ENGINE = 'mysqli';


    /**
     * construct
     *
     * @param $server
     *   A string containing the IP or URL to the database server.
     * @param $database
     *   A string containing the name of the database for this connection.
     * @param $username
     *   A string containing the username for the connection's security login.
     * @param $password
     *   A string containing the password for the connection's security login.
     * @param $persistent
     *   (optional) Setting to TRUE will enable persistent connections. [FALSE]
     */
    public function __construct($server, $database, $username, $password,
                                $persistent = false, $tablePrefix = '')
    {
        $this->dateFormat = 'Y-m-d';
        $this->dtsFormat = 'Y-m-d H:i:s';

//        $this->enableLog();

        parent::__construct($server, $database, $username, $password,
            $persistent, $tablePrefix);

    }


    /**
     * Dynamic Get Function Override
     *
     * @param $name
     *   A string containing the name of the property to get.
     *
     * @return
     *   Value of the property.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'insertID':
                $return = $this->_connection->insert_id;
                break;

            default:
                $return = parent::__get($name);
        }

        return $return;
    }


    private function _getConnection()
    {
        if (!isset($this->_connection)) {
            $this->open();
        }

        return $this->_connection;
    }


    public function isConnected()
    {
        $return = isset($this->_connection);

        if ($return) {
            $return = $this->_connection->ping();

            if (!$return) {
                $this->_logWarning($this->_connection->error, 'MySQL Lost Connection');
            }
        }

        return $return;
    }


    public function close()
    {
        $return = $this->_connection->close();

        if ($return) {
            unset($this->_connection);
        } else {
            $msg = 'Unable to close database!';
            $this->_logError($msg, 'MySQL Error');
        }

        return $return;
    }


    public function execute($sql)
    {
        $return = true;

        if (!isset($this->_connection)) {
            $return = $this->open();
        }

        if ($return) {
            $this->_logVerbose($sql);

            $return = $this->_connection->query($sql);

            if ($return) {
                $return = new anvilData_mysqli_Recordset($sql, $return, $this);
            }
        }

        if ($return === false) {
            $msg = '[' . $this->_connection->errno . '] ' . $this->_connection->error;
            $this->_logError($msg, 'MySQL Query Error');
//        } else {
//        } elseif ($return !== true) {
//            $return = new anvilData_mysqli_Recordset($sql, $return, $this);
        }

        return $return;
    }


    /**
     * @param      $sql string
     *
     * @param bool $returnRecordsets
     *
     * @return array
     */
    public function executeMulti($sql, $returnRecordsets = true)
    {
        $return = true;

        if (!isset($this->_connection)) {
            $return = $this->open();
        }

        if ($return) {
            $this->_logVerbose($sql);

            $return = $this->_connection->multi_query($sql);

            if ($return) {
                if ($returnRecordsets) {
//                    while ($this->_connection->next_result()) {
//                        if ($result = $this->_connection->store_result()) {
//                            $return[] = new anvilData_mysqli_Recordset($sql, $result, $this);
//                        }
//                    }
                    do {
                        /* store first result set */
                        if ($result = $this->_connection->use_result()) {
                            $return[] = new anvilData_mysqli_Recordset($sql, $result, $this);
                        }
                        /* print divider */
//                        if ($this->_connection->more_results()) {
//                        }
                    } while ($this->_connection->more_results() && $this->_connection->next_result());
                } else {
                    while ($this->_connection->more_results() && $this->_connection->next_result()) {};

                    //                    if ($this->_connection->use_result()) {
//                        while ($this->_connection->next_result()) {
//                            $result = $this->_connection->use_result();
//                            $result->free();
//                            ;
//                        }
//                    }
                }
            }
        }

        if ($return === false) {
            $msg = '[' . $this->_connection->errno . '] ' . $this->_connection->error;
            $this->_logError($msg, 'MySQL Query Error');
//        } else {
//        } elseif ($return !== true) {
//            $return = new anvilData_mysqli_Recordset($sql, $return, $this);
        }

        return $return;
    }


    public function open()
    {
        $return = true;

        if (!isset($this->_connection) || (isset($this->_connection) && !$this->isConnected())) {

            if (substr(strtolower($this->server), 0, 2) == 'p:') {
                $this->_logVerbose('Opening Persistent Database Connection...');
            } else {
                $this->_logVerbose('Opening Database Connection...');
            }

            $this->_connection = new mysqli($this->server, $this->username, $this->password, $this->database, $this->port);

//            if ($this->_connection->connect_error) {
            if ($this->_connection->connect_errno) {
                    $this->_logError('(' . $this->_connection->connect_errno . ') ' . $this->_connection->connect_error, 'MySQL Connection Error');
                $return = false;
            }
        }

        return $return;
    }


    public function dbString($value)
    {
        $return = 'null';

        if ($value && $this->isConnected()) {
            $return = "'" . $this->_connection->real_escape_string($value) . "'";
        }

        return $return;
    }

    // }}}
}


?>
