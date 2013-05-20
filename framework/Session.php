<?php
/*
 * phpAnvil Framework
 *
 * Copyright (c) 2009-2011 Nick Slevkoff
 *
 * LICENSE
 *
 * This source file is subject to the MIT License that is bundled with this
 * package in the file LICENSE.md.  It is also available online at the following:
 * - http://www.phpanvil.com/LICENSE.md
 * - http://www.opensource.org/licenses/mit-license.php
 */

namespace phpAnvil\Framework;

use phpAnvil\Component\ObjectAbstract;

/**
 * Session class for the %phpAnvil %Framework.
 *
 * @copyright   Copyright (c) 2009-2011 Nick Slevkoff
 * @license     MIT License
 *      Full copyright and license information is available in the LICENSE.md
 *      file that was distributed with this source file or can be found online
 *      at http://www.phpanvil.com/LICENSE.md
 */
class Session extends ObjectAbstract
{

//	private $_cookieDetectionEnabled = false;
    private $_cookieDetected = false;
    private $_resetEnabled = false;
    private $_new = true;

    private $_timezoneDetectionEnabled = false;

    public $dataConnection;

    private $_detectExecuted = false;
    private $_openExecuted = false;
    private $_abandonExecuted = false;


    public $id;
    public $phpSessionID;
    public $sessionDTS;
    public $lastVisitDTS;
    public $thisVisitDTS;
    public $requestedURL;
    public $userAgent;
    public $userIP;
    public $userID;
    public $referrer;
    public $innactiveTimeout;
    public $sessionLifespan;
    public $dataName;
    public $sessionName;
    public $cookieDomain;
//	public $testCookieName;
//	public $testParameterName;
    public $sessionsTable;
    public $varsTable;
    public $timezoneOffset = '';
    public $dateTimeZone;


    public function __construct($atDataConnection = null)
    {

        unset($this->id);
        unset($this->phpSessionID);
        unset($this->sessionDTS);
        unset($this->lastVisitDTS);
        unset($this->thisVisitDTS);
        unset($this->requestedURL);
        unset($this->userAgent);
        unset($this->userIP);
        unset($this->userID);
        unset($this->referrer);
        unset($this->innactiveTimeout);
        unset($this->sessionLifespan);
        unset($this->dataName);
        unset($this->sessionName);
        unset($this->cookieDomain);
        //		unset($this->testCookieName);
        //		unset($this->testParameterName);
        unset($this->sessionsTable);
        unset($this->varsTable);


        $this->addProperty('id', 0);
        $this->addProperty('phpSessionID', 0);
        $this->addProperty('sessionDTS', '');
        $this->addProperty('lastVisitDTS', '');
        $this->addProperty('thisVisitDTS', '');
        $this->addProperty('requestedURL', '');

        $this->addProperty('userAgent', '');
        $this->addProperty('userIP', '');
        $this->addProperty('userID', '');
        $this->addProperty('referrer', '');

        $this->addProperty('innactiveTimeout', 1800);
        $this->addProperty('sessionLifespan', 7200);

        $this->addProperty('dataName', '$d474');
        $this->addProperty('sessionName', 'ATSESSION');
        $this->addProperty('cookieDomain', '/');

        //		$this->addProperty('testCookieName', 'ats_tc');
        //		$this->addProperty('testParameterName', 'ats_tp');
        $this->addProperty('sessionsTable', 'at_sessions');
        $this->addProperty('varsTable', 'at_session_vars');


        $this->dataConnection = $atDataConnection;

        ini_set('session.name', $this->sessionName);

        parent::__construct();
    }


    /**
     * Detect Data for atSession Use
     */
    public function detect()
    {
        if ($this->_detectExecuted) {
            $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'Already Executed - skipping...');
            FB::info('Already Executed - skipping...');
        } else {
            $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");
            FB::info('Executing...');

            $this->thisVisitDTS = date('Y-m-d H:i:s');

            #---- Detect User IP
            $this->userIP = $_SERVER['REMOTE_ADDR'];
            $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'User IP = ' . $this->userIP);
            //            FB::log($this->userIP, '$this->userIP');

            #---- Detect User Agent
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
                $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'User Agent = ' . $this->userAgent);
                //                FB::log($this->userAgent, '$this->userAgent');
            }

            //            FB::log($this->sessionName, '$this->sessionName');
            //            FB::log($_REQUEST[$this->sessionName], '$_REQUEST[$this->sessionName]');


            #---- Detect Existing Session
            if (isset($_COOKIE[$this->sessionName])) {
                //                FB::log('Detected existing session...');

                $this->_new = false;
                //				$this->id = $_REQUEST[$this->sessionName];
                $this->phpSessionID = $_COOKIE[$this->sessionName];

                #---- Session Cookie Detection
                //				$this->_cookieDetected = isset($_COOKIE[$this->sessionName]);
                $this->_cookieDetected = true;

                $this->id = $_COOKIE[$this->sessionName];
                //				$this->phpSessionID = $_COOKIE[$this->sessionName];
                $msg = 'atSession Cookie Detected';
                $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, $msg);
                FB::info($msg);


                #---- Test Parameter Detection
                //				if (isset($_GET[$this->testParameterName])) {
                //					$this->_new = true;
                //					$msg = 'Cookie Test Parameter Detected - New Session';
                //                        $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, $msg);
                //                        FB::info($msg);
                //				}

                #---- Reset Detection
                if ($this->_resetEnabled) {
                    $this->_new = true;
                    $msg = 'Reset Detected - New Session';
                    $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, $msg);
                    FB::warn($msg);
                }
                #---- Check and Load Session
                /*			} else {
                                #---- Security and Age Detection
                //				$this->phpSessionID = $_REQUEST[$this->sessionName];
                                $sql = 'SELECT * FROM ' . $this->sessionsTable;

                //				if ($this->isCookieDetected()) {
                                    $sql .= ' WHERE session_id = ' . $this->id;
                //				} else {
                //					$sql .= ' WHERE ascii_session_id = ' . $this->devData->dbString($this->phpSessionID);
                //				}

                                $objRS = $this->devData->execute($sql);
                                if ($objRS->read()) {
                                    $this->id = $objRS->data('session_id');
                                    $this->userID = $objRS->data('user_id');

                                    $this->new = false;

                                    setcookie($this->cookieName, $this->id, $this->sessionLifespan, $this->cookieDomain);

                                    $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'Session #' . $this->id . ' Loaded for ' . $this->userIP);
                                }
                                $objRS->close();
                            }
                            $this->save();
                */

                #---- Cookie Test Detection
                //			} elseif (isset($_COOKIE[$this->testCookieName])) {
                //				$this->_cookieDetected = true;
                //				$msg = 'Test Cookie Detected';
                //                        $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, $msg);
                //                        FB::info($msg);

                #---- Cookie Test Detection (passed variable and no cookie)
                //			} elseif (isset($_GET[$this->testParameterName])) {
                //				#---- No Cookie Support
                //				ini_set('session.use_trans_sid', '1');

                //				$msg = 'No Cookie Support Detected - Passing ID as a Query String Parameter';
                //                        $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, $msg);
                //                        FB::warn($msg);
            }

        }
        //		$this->_detectExecuted = true;
    }


    private function renderTimezoneDetectionHTML($url = '')
    {

        $html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">' . "\n";
        $html .= '<html><head>' . "\n";
        $html .= '<script type="text/javascript">' . "\n";
        //        $html .= 'function b(){' . "\n";
        $html .= 'var d = new Date();' . "\n";
        //        $html .= 'var tza = d.toLocaleString().split(" ").slice(-1)';
        $html .= 'var tzo = (d.getTimezoneOffset() / -60);' . "\n";
        //        $html .= 'window.location = window.location + '?tzinfo=' + tza + '|' + tzo';
        //        $html .= 'window.location.href = "' . $url . 'tzo=" + tzo;';
        $html .= 'location.href = "' . $url . 'tzo=" + tzo;' . "\n";
        //        $html .= '}' . "\n";
        $html .= '</script>' . "\n";
        //        $html .= '</head><body onload="b();"></body></html>' . "\n";
        $html .= '</head><body></body></html>' . "\n";

        //        FB::log($url, '$url');
        //        FB::log($html, '$html');

        echo $html;
    }


    /**
     * Opens atSession and Starts the PHP Session
     */
    public function open()
    {
        $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");

        ini_set('session.name', $this->sessionName);
        session_name($this->sessionName);

        $this->detect();

        //        $redirectURL = 'http://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        //        if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
        //            $redirectURL .= '&';
        //        } else {
        //            $redirectURL .= '?';
        //        }

        //		if ($this->_cookieDetectionEnabled) {
        //			$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'Auto Cookie Detection Starting...');

        //			session_start();
        //			setcookie($this->testCookieName, 'y', 0, '/');

        //			$redirectURL = 'http://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        //			if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
        //				$redirectURL .= '&';
        //			} else {
        //				$redirectURL .= '?';
        //			}
        //			$redirectURL .=  $this->testParameterName . '=y';

        //            if ($this->_timezoneDetectionEnabled)
        //            {
        //                $this->renderTimezoneDetectionHTML($redirectURL);
        //            } else {
        //                $redirectURL .=  $this->testParameterName . '=y';
        //			    header('Location: ' . $redirectURL);
        //            }

        //			die();
        //        } elseif ($this->_timezoneDetectionEnabled && empty($this->timezoneOffset)) {
        //            $this->renderTimezoneDetectionHTML($redirectURL);
        //            die();
        //		} else {

        ini_set('session.save_handler', 'user');
        ini_set('session.gc_probability', 0);

        session_set_save_handler(
            array($this, 'executeOpen'),
            array($this, 'executeClose'),
            array($this, 'executeRead'),
            array($this, 'executeWrite'),
            array($this, 'executeDestroy'),
            array($this, 'executeGC')
        );

        register_shutdown_function('session_write_close');
        $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'session_set_save_handler set');


        session_start();

        $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'Session #' . $this->id . ' (' . $this->phpSessionID . ') Started');

        //			if ($this->_resetEnabled) {
        //				session_regenerate_id();
        //			}


        #==== User Timezone Detection ======================================
        if ($this->isCookieDetected() && $this->_timezoneDetectionEnabled) {
            //---- Get non-phpAnvil Querystring ----------------------------
            $queryString = '';
            foreach ($_GET as $param => $value)
            {
                switch ($param) {
                    case 'm':
                    case 'a':
                    case 'tzo':
                        break;

                    default:
                        $queryString .= '&' . $param . '=' . $value;
                }
            }

            if (!empty($queryString)) {
                $queryString = substr($queryString, 1);
            }

            $_SESSION['queryString'] = $queryString;

            //---- Return Trip with the Timezone Offset Value? -------------
            if (isset($_GET['tzo'])) {
                $this->timezoneOffset = $_GET['tzo'];
                $msg = 'User Timezone Detected (' . $this->timezoneOffset . ')';
                $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, $msg);
                FB::info($msg);
                $_SESSION['timezoneOffset'] = $this->timezoneOffset;
                //                    setcookie('timezoneOffset', $this->timezoneOffset, $this->sessionLifespan, $this->cookieDomain);

                //---- Redirect without the querystring
                //                    $redirectURL = 'http://' . $_SERVER["SERVER_NAME"] . str_replace('?tzo=' . $this->timezoneOffset, '', str_replace('&tzo=' . $this->timezoneOffset, '', $_SERVER["REQUEST_URI"]));

                $redirectURL = 'http';

                if ($_SERVER["SERVER_PORT"] == 443) {
                    $redirectURL .= 's';
                }

                $redirectURL .= '://' . $_SERVER["SERVER_NAME"];

                //                    if (isset($_SESSION['requestURI']))
                //                    {
                //                        $redirectURL .= $_SESSION['requestURI'];
                //                    }

                if (isset($_SERVER["REDIRECT_URL"])) {
                    $redirectURL .= $_SERVER['REDIRECT_URL'];
                }

                if (!empty($queryString)) {
                    $redirectURL .= '?' . $queryString;
                }

                header('Location: ' . $redirectURL);
                //                    die();

            } elseif (isset($_SESSION['timezoneOffset'])) {
                $this->timezoneOffset = $_SESSION['timezoneOffset'];
                //                    $msg = 'User Timezone Detected in Session (' . $this->timezoneOffset . ')';
                //                    $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, $msg);
                //                    FB::info($msg);
                //                } elseif (isset($_COOKIE['timezoneOffset'])) {
                //                    $this->timezoneOffset = $_COOKIE['timezoneOffset'];
                //                    $msg = 'User Timezone Detected (' . $this->timezoneOffset . ')';
                //                    $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, $msg);
                //                    FB::info($msg);
            } elseif (empty($this->timezoneOffset)) {
                //                } elseif ($this->timezoneOffset == '') {
                $_SESSION['requestURI'] = $_SERVER["REQUEST_URI"];
                $redirectURL = 'http://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];

                if (empty($queryString)) {
                    $redirectURL .= '?';
                } else {
                    $redirectURL .= '&';
                }

                $this->renderTimezoneDetectionHTML($redirectURL);
                //                die();
            }
            if (!empty($this->timezoneOffset)) {
                $this->dateTimeZone = new DateTimeZone('Etc/GMT' . $this->timezoneOffset);
                //                    FB::info($this->dateTimeZone->getName() . ' | ' . $this->dateTimeZone->getLocation(), 'Timezone');
            }
        }

        //		}
    }


    public function loadForUser($userID)
    {
        $return = false;

        $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'Loading Last Session for User ID#' . $userID . '...');
        $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'Current Session #' . $this->id . '.');

        $sql = 'SELECT *';
        $sql .= ' FROM ' . $this->sessionsTable;
        $sql .= ' WHERE user_id=' . $userID;
        $sql .= ' ORDER BY last_visit_dts DESC';

        $objRS = $this->dataConnection->execute($sql);

        if ($objRS->hasRows()) {
            $objRS->read();
            $this->userID = $userID;
            $this->id = $objRS->data('session_id');
            $this->executeRead($objRS->data('ascii_session_id'));
            $return = true;
            $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'Session #' . $this->id . ' Found!');
        }

        return $return;
    }


    /**
     * Enables cookie detection.
     *
     */
//	public function enableCookieDetection() {
//		$this->_cookieDetectionEnabled = true;
//	}

    public function enableTimezoneDetection()
    {
        $this->_timezoneDetectionEnabled = true;
    }


    /**
     * Logs the visit date and time to the sessions table.
     *
     */
    public function logVisit()
    {
        if ($this->id > 0) {
            $sql = 'UPDATE ' . $this->sessionsTable . ' SET ';
            $sql .= 'last_visit_dts=NOW()';
            $sql .= ' WHERE session_id=' . $this->id;
            //			$sql .= ' WHERE session_id=' . $this->dataConnection->dbString($this->id);
            return $this->dataConnection->execute($sql);
        } else {
            return false;
        }
    }


    /**
     * Determines if the user is new.
     *
     * @return
     *   TRUE if the user is new, otherwise FALSE.
     *
     */
    public function isNew()
    {
        return $this->_new;
    }


    /**
     * Determines if cookies have been detected as enabled for the user.
     *
     * @return
     *   Returns TRUE if cookies have been detected for the user.
     *
     */
    public function isCookieDetected()
    {
        return $this->_cookieDetected;
    }


    /**
     * Determines if a reset has been enabled.
     *
     */
    public function isReset()
    {
        return $this->_resetEnabled;
    }


    /**
     * Returns PHP's session identifier.
     *
     * @return
     *   Returns a string with PHP's internal session ID.
     *
     */
    public function getSessionIdentifier()
    {
        return $this->_phpSessionID;
    }


    public static function getValue($name, $default = false)
    {
        //global $_SESSION;
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "parameters = ($name, $default)", self::TRACE_TYPE_DEBUG);

        if (!in_array($name, $_SESSION) || !isset($_SESSION[$name])) {
            $_SESSION[$name] = $default;
        }

        return $_SESSION[$name];
    }


    public function setValue($name, $value)
    {
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "parameters = ($name, $value)", self::TRACE_TYPE_DEBUG);

        $_SESSION[$name] = $value;

        return true;
    }


    public function __get($name)
    {
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "parameters = ($name)", self::TRACE_TYPE_DEBUG);

        if ($this->isProperty($name)) {
            return parent::__get($name);
        } else {
            return $this->getValue($name);
        }
    }


    public function __set($name, $value)
    {
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "parameters = ($name, $value)", self::TRACE_TYPE_DEBUG);

        if ($this->isProperty($name)) {
            return parent::__set($name, $value);
        } else {
            return $this->setValue($name, $value);
        }
    }


    public function getSpecialValue($name, $default = false)
    {
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "parameters = ($name)", self::TRACE_TYPE_DEBUG);

        if ($this->id > 0) {
            //if (TRACE && $this->_isTraceEnabled) DevTrap::addTraceInfo(__FILE__, __METHOD__, __LINE__, "getting from DB...");

            $sql = 'SELECT value FROM ' . $this->varsTable;
            $sql .= ' WHERE session_id=' . $this->id;
            $sql .= ' AND name=' . $this->dataConnection->dbString($name);

            $objRS = $this->dataConnection->execute($sql);
            if ($objRS->read()) {
                return unserialize($objRS->data('value'));
            } else {
                return $default;
            }
            $objRS->close();
        } else {
            return $default;
        }
    }


    public function setSpecialValue($name, $value)
    {
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "parameters = ($name, $value)", self::TRACE_TYPE_DEBUG);

        if ($this->id > 0) {
            //if (TRACE && $this->_isTraceEnabled) DevTrap::addTraceInfo(__FILE__, __METHOD__, __LINE__, "saving to DB...");

            $sql = 'INSERT INTO ' . $this->varsTable . ' (session_id, name, value)';
            $sql .= ' VALUES (';

            $sql .= $this->id;
            $sql .= ',' . $this->dataConnection->dbString($name);
            $sql .= ',' . $this->dataConnection->dbString(serialize($value));
            $sql .= ')';
            $sql .= ' ON DUPLICATE KEY UPDATE';
            $sql .= ' value=' . $this->dataConnection->dbString(serialize($value));

            //if (TRACE && $this->_isTraceEnabled) DevTrap::addTraceInfo(__FILE__, __METHOD__, __LINE__, $sql);

            return $this->dataConnection->execute($sql);
        } else {
            return false;
        }
    }


    /**
     * Replacement Callback for Session Open
     */
    public function executeOpen($save_path, $session_name)
    {
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "parameters = ($save_path, $session_name)", self::TRACE_TYPE_DEBUG);

        //echo('<br>** atSession.OpenCallback (' . $save_path . ', ' . $session_name . ') **<br>');

        # Do nothing
        return (true);
    }


    /**
     * Replacement Callback for Session Close
     */
    public function executeClose()
    {
        $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");

        //echo('<br>** atSession.CloseCallback () **<br>');

        //pg_close($this->dbhandle);
        return (true);
    }


    /**
     * Replacement Callback for Session Read
     */
    public function executeRead($phpSessionID)
    {
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "parameters = ($phpSessionID)", self::TRACE_TYPE_DEBUG);

        $return = '';

        if (!$this->_resetEnabled) {
            //echo('<br>** atSession.ReadCallback (' . $id . ') **<br>');

            # We use this to determine whether or not our session actually exists.
            //$strUserAgent = $GLOBALS["HTTP_USER_AGENT"];
            $this->phpSessionID = $phpSessionID;
            # Set failed flag to 1 for now
            //$failed = 1;
            #---- See if this exists in the database or not.
            //			$sql = 'SELECT *, TIMESTAMPDIFF(SECOND, last_visit_dts, Now()) AS time_diff';
            $sql = 'SELECT *, UNIX_TIMESTAMP(Now()) - UNIX_TIMESTAMP(last_visit_dts) AS time_diff, NOW() AS this_visit_dts';
            $sql .= ' FROM ' . $this->sessionsTable;


            //---- Disabling direct ID lookup since we don't always have that.
            //			if ($this->id > 0) {
            //				$sql .= ' WHERE session_id = ' . $this->id;
            //			} else {
            $sql .= ' WHERE ascii_session_id = ' . $this->dataConnection->dbString($this->phpSessionID);
            //			}


            //			$sql .= ' AND TIMESTAMPDIFF(SECOND, last_visit_dts, Now()) <= ' . $this->innactiveTimeout;
            $sql .= ' AND UNIX_TIMESTAMP(Now()) - UNIX_TIMESTAMP(last_visit_dts) <= ' . $this->innactiveTimeout;

            $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, $sql, self::TRACE_TYPE_DEBUG);
            //echo($sql);

            $objRS = $this->dataConnection->execute($sql);
            if ($objRS->read()) {
                $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'Session found.');

                $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'Is New? = ' . $this->isNew(), self::TRACE_TYPE_DEBUG);
                $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'Innactive Timeout = ' . $this->innactiveTimeout . 's', self::TRACE_TYPE_DEBUG);
                $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'Time Diff = ' . $objRS->data('time_diff') . 's', self::TRACE_TYPE_DEBUG);

                $this->userID = $objRS->data('user_id');

                if ($objRS->data('time_diff') <= $this->innactiveTimeout) {
                    $this->id = $objRS->data('session_id');
                    $this->sessionDTS = $objRS->data('session_dts');
                    //				$this->phpSessionID = $objRS->data('ascii_session_id');
                    //					$this->userIP = $objRS->data('user_ip');
                    $this->lastVisitDTS = $objRS->data('last_visit_dts');
                    $this->thisVisitDTS = $objRS->data('this_visit_dts');
                } else {
                    $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'Session Timed Out - Starting New Session...'); //, DevTrace::TYPE_WARNING);
                    $this->id = 0;
                    $this->userID = 0;
                }
            } else {
                $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'Session NOT Found - Starting New Session...'); //, DevTrace::TYPE_WARNING);

                $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'Innactive Timeout = ' . $this->innactiveTimeout . 's', self::TRACE_TYPE_DEBUG);
                $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'Time Diff = ' . $objRS->data('time_diff') . 's', self::TRACE_TYPE_DEBUG);

                $this->id = 0;
                $this->userID = 0;
            }
            $this->save();

            $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, 'Is New? = ' . $this->isNew(), self::TRACE_TYPE_DEBUG);

            #---- Get Session Data from DB
            $sql = 'SELECT value';
            $sql .= ' FROM ' . $this->varsTable;
            $sql .= ' WHERE session_id = ' . $this->id;
            $sql .= ' AND name = ' . $this->dataConnection->dbString($this->dataName);

            $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, $sql, self::TRACE_TYPE_DEBUG);
            //echo($sql);

            $objRS = $this->dataConnection->execute($sql);
            if ($objRS->read()) {
                $return = $objRS->data('value');

                $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Session Data Found ($return)", self::TRACE_TYPE_DEBUG);
            }
        }

        return $return;
    }


    /**
     * Replacement Callback for Session Write
     */
    public function executeWrite($id, $sess_data)
    {
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "parameters = ($id, $sess_data)", self::TRACE_TYPE_DEBUG);

        //			if (is_object($this->_devData)) {
        //				if (TRACE && $this->_isTraceEnabled) DevTrap::addTraceInfo(__FILE__, __METHOD__, __LINE__, 'devData IS an object');
        //			} else {
        //				if (TRACE && $this->_isTraceEnabled) DevTrap::addTraceInfo(__FILE__, __METHOD__, __LINE__, 'devData IS NOT an object');
        //			}
        //
        //			$this->_devData->open(true);
        //
        //		//echo('<br>** atSession.WriteCallback (' . $id . ', ' . $sess_data . ') **<br>');
        //

        /*
                    $sql = 'INSERT INTO ' . $this->varsTable . ' (session_id, name, value)';
                    $sql .= ' VALUES (';

                    $sql .= $this->id;
                    $sql .= ',' . self::$devData2->dbString($this->dataName);
                    $sql .= ',' . self::$devData2->dbString($sess_data);
                    $sql .= ')';
                    $sql .= ' ON DUPLICATE KEY UPDATE';
                    $sql .= ' value=' . self::$devData2->dbString($sess_data);

                    $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, $sql, self::TRACE_TYPE_DEBUG);


                    return self::$devData2->execute($sql);
        */
        return true;
    }


    /**
     * Replacement Callback for Session Destroy
     */
    public function executeDestroy($id)
    {
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "parameters = ($id)", self::TRACE_TYPE_DEBUG);

        //echo('<br>** atSession.DestroyCallback (' . $id . ') **<br>');

        $_SESSION = array();

        if ($this->id > 0) {

            //$sql = 'DELETE FROM ' . SQL_TABLE_DS_SESSIONS;
            //$sql .= ' WHERE ascii_session_id=' . $this->_devData->dbString($id);

            //return $this->_devData->execute($sql);
            return $this->delete();
        } else {
            return false;
        }
    }


    /**
     * Replacement Callback for Session GC
     */
    public function executeGC($maxlifetime)
    {
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "parameters = ($maxlifetime)", self::TRACE_TYPE_DEBUG);

        //echo('<br>** atSession.GCCallback (' . $maxlifetime . ') **<br>');

        return (true);
    }


    #---- Save the Data Record to the DB
    public function save()
    {
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");

        if ($this->id == 0) {
            $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Saving New Session...");

            //echo('*** save (INSERT) ***');
            $this->_new = true;

            $sql = 'INSERT INTO ' . $this->sessionsTable . ' (session_dts, ascii_session_id, user_ip, user_id, last_visit_dts, user_agent, request_uri, referrer, is_cookie_detected)';
            $sql .= ' VALUES (';

            //			$sql .= $this->devData->dbDTS($this->thisVisitDTS);
            $sql .= 'Now()';
            $sql .= ',' . $this->dataConnection->dbString($this->phpSessionID);
            $sql .= ',' . $this->dataConnection->dbString($this->userIP);
            $sql .= ',' . $this->userID;
            //			$sql .= ',' . $this->dataConnection->dbDTS($this->thisVisitDTS);
            $sql .= ',Now()';
            $sql .= ',' . $this->dataConnection->dbString($this->userAgent);
            $sql .= ',' . $this->dataConnection->dbString('http://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);

            if (isset($_SERVER["HTTP_REFERER"])) {
                //$this->_strReferrer = $_SERVER["HTTP_REFERER"];
                $sql .= ',' . $this->dataConnection->dbString($_SERVER["HTTP_REFERER"]);
            } else {
                $sql .= ',null';
            }

            $sql .= ',' . $this->dataConnection->dbBoolean($this->_cookieDetected);
            //            $sql .= ',-1';
            $sql .= ')';

        } else {
            $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Saving Existing Session...");

            //$this->_isNew = false;

            $sql = 'UPDATE ' . $this->sessionsTable . ' SET';

            //$sql .= 'ascii_session_id=' . $this->_devData->dbString($this->_phpSessionID);
            //$sql .= ', user_ip=' . $this->_devData->dbString($this->_userIP);
            $sql .= ' user_id=' . $this->userID;
            //$sql .= ', user_agent=' . $this->_devData->dbString($this->_userAgent);
            //			$sql .= ', last_visit_dts=' . $this->devData->dbDTS($this->thisVisitDTS);
            $sql .= ', last_visit_dts=Now()';
            $sql .= ', is_cookie_detected=' . $this->dataConnection->dbBoolean($this->_cookieDetected);
            //            $sql .= ', is_cookie_detected=-1';

            $sql .= ' WHERE session_id=' . $this->id;
        }

        $isOk = $this->dataConnection->execute($sql);

        if ($this->id == 0) {
            $sql = "SELECT LAST_INSERT_ID() AS id, Now() AS nowDTS;";
            $objRS = $this->dataConnection->execute($sql);
            if ($objRS->read()) {
                $this->id = $objRS->data('id');
                $this->lastVisitDTS = $objRS->data('nowDTS');
                $this->thisVisitDTS = $objRS->data('nowDTS');
            }
            $objRS->close();
        }

        return $isOk;
    }


    #---- Delete the Data Record from the DB
    public function delete()
    {
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");

        if ($this->id > 0) {
            //$sql = 'DELETE FROM ' . $this->sessionsTable;
            //$sql .= ' WHERE session_id=' . $this->_id;
            $sql = 'UPDATE ' . $this->sessionsTable;
            $sql .= ' SET record_status_id=' . 30; #---- deleted
            $sql .= ' WHERE session_id=' . $this->id;
        }

        $this->dataConnection->execute($sql);
        return true;
    }


    public function abandon()
    {
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");

        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
            unset($_COOKIE[$this->sessionName]);
        }

        // Finally, destroy the session.
        session_destroy();

        session_regenerate_id(true);

        //		$this->_cookieDetectionEnabled = false;
        $this->_timezoneDetectionEnabled = false;
        $this->_abandonExecuted = true;
        $this->_detectExecuted = false;
        $this->id = 0;
        $this->_new = true;
    }


    public function __destruct()
    {
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");
        //if (TRACE && $this->_isTraceEnabled) DevTrap::addTraceInfo(__FILE__, __METHOD__, __LINE__, "parameters = ()");
        //session_write_close();
    }


    public function close()
    {
        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "Executing...");

        //		session_write_close();
        //		$this->executeWrite($this->id, serialize($_SESSION));

        //		$this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "parameters = ()");

        //		$this->save();

        //		$sess_data = serialize($_SESSION);
        $return = true;

        //        FB::info('Closing Session...');
        //        FB::log($this->isNew(), '$this->isNew()');

        if (!$this->isNew()) {
            $sess_data = session_encode();

            $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, "\$sess_data = $sess_data", self::TRACE_TYPE_DEBUG);

            //            FB::log($sess_data, '$sess_data');

            $sql = 'INSERT INTO ' . $this->varsTable . ' (session_id, name, value)';
            $sql .= ' VALUES (';

            $sql .= $this->id;
            $sql .= ',' . $this->dataConnection->dbString($this->dataName);
            $sql .= ',' . $this->dataConnection->dbString($sess_data);
            $sql .= ')';
            $sql .= ' ON DUPLICATE KEY UPDATE';
            $sql .= ' value=' . $this->dataConnection->dbString($sess_data);

            $this->addTraceInfo(__FILE__, __METHOD__, __LINE__, $sql, self::TRACE_TYPE_DEBUG);

            //                FB::log($sql);


            $return = $this->dataConnection->execute($sql);
        }
        return $return;

    }
}
