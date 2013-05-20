<?php
namespace phpAnvil\Framework;

use phpAnvil\Component\ComponentAbstract;
//use phpAnvil\Framework\LogRouter;
//use phpAnvil\Framework\RequestRouter;

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


//use app\Application;

/**
 * Core kernel class for the %phpAnvil %Framework.
 *
 * @copyright   Copyright (c) 2009-2011 Nick Slevkoff
 * @license     MIT License
 *      Full copyright and license information is available in the LICENSE.md
 *      file that was distributed with this source file or can be found online
 *      at http://www.phpanvil.com/LICENSE.md
 */
class Kernel extends ComponentAbstract
{

    public $autoClassLoader;
    public $request;
    public $response;
    public $session;

    public $db = null;

    public $site;
    public $application;
    public $user;

    public $log;

    public $regional = null;
    public $modelDictionary = null;


    public function __construct()
    {
        $this->enableLog();

        $this->log = new LogRouter();
        $this->request = new RequestRouter();
    }


    function init()
    {
        $return = true;

        $this->addVerboseLog('Loading Application...');
        $this->application = new \app\Application();
        //        $this->application = new Application();
        $this->addInfoLog($this->application->name . ' v' . $this->application->version . '.' . $this->application->build . ' loaded.');


        //---- Check if Application is Set
        if (isset($this->application)) {
            //---- Initialize the Application
            $return = $this->application->init();

        } else {
            $this->addErrorLog('Application not set in phpAnvil.');
        }

        return $return;
    }


    function open()
    {
        /*
               #--- Set Server App Timezone
               if (version_compare(phpversion(), "5.1.0", ">"))
               {
                   date_default_timezone_set($this->site->timeZone);
               }


               //---- Start Session
               $this->session->dataConnection = $this->db;
       //        $this->session->enableTrace();
       //        $this->session->innactiveTimeout = 60 * 60;
               $this->session->open();

               $this->regional->timezoneOffset = $this->session->timezoneOffset;

               if (!empty($this->regional->timezoneOffset))
               {
                   $this->regional->dateTimeZone = new DateTimeZone('Etc/GMT' . $this->regional->timezoneOffset);
               }

               $this->triggerEvent('phpAnvil.open');


               //---- Check if Application is Set
               if (isset($this->application))
               {
                   //---- Open the Application
                   $this->application->open();

               } else {
                   FB::error('Application not set in phpAnvil.');
               }


        */
    }


    public function execute()
    {
        $this->addLog('Executing phpAnvil application...', 'phpAnvil->execute()', self::LOG_LEVEL_BRIEF_INFO);


        $this->startLogGroup('Initializing...');

        if ($this->init()) {
            $this->endLogGroup('Initializing...');
            $this->startLogGroup('Opening...');

            $this->open();

            $this->endLogGroup('Opening...');
            $this->startLogGroup('Closing...');

            $this->close();

            $this->endLogGroup('Closing...');
        } else {
            $this->endLogGroup('Initializing...');
        }

        $this->addInfoLog('END OF LINE.');

    }


    public function close()
    {
        /*
               //---- Check if Application is Set
               if (isset($this->application))
               {
                   //---- Open the Application
                   $this->application->close();


               } else {
                   FB::error('Application not set in phpAnvil.');
               }


               $this->triggerEvent('phpAnvil.close');


               $this->session->close();

               FB::log('session closed');

               sendDebugTrace();
       //		$this->db->close();


        */
    }
}
