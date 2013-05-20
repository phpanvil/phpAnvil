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
 * Base abstract class for all %application classes.
 *
 * @copyright   Copyright (c) 2009-2011 Nick Slevkoff
 * @license     MIT License
 *      Full copyright and license information is available in the LICENSE.md
 *      file that was distributed with this source file or can be found online
 *      at http://www.phpanvil.com/LICENSE.md
 */
class ApplicationAbstract extends ObjectAbstract
{

    public $name;
    public $version;
    public $build;
    public $copyright;


//    public $configFilename;

//    public $defaultModule;
//    public $defaultAction;
//    public $loginModule;
//    public $loginAction;
//    public $requestedModule;
//    public $requestedAction;


    function __construct()
    {

        return true;
    }


    function init()
    {
        global $phpAnvil;

        $return = false;

        //        $this->loadConfig();

        //        $phpAnvil->triggerEvent('application.init');


        //---- Check if Site is Set
        //        if (isset($phpAnvil->site))
        //        {
        //---- Initialize the Site
        //            $phpAnvil->site->init();
        //            $return = true;
        //        } else {
        //            FB::error('Site not set in phpAnvil.');
        //        }

        //        return $return;
    }


    function open()
    {
        global $phpAnvil;

        //        $phpAnvil->triggerEvent('application.open');
        //
        //
        //        //---- Check if Site is Set
        //        if (isset($phpAnvil->site))
        //        {
        //            //---- Initialize the Site
        //            $phpAnvil->site->open();
        //            $return = true;
        //        } else {
        //            FB::error('Site not set in phpAnvil.');
        //        }

        //        return $return;
    }


    function close()
    {
        global $phpAnvil;

        //        //---- Check if Site is Set
        //        if (isset($phpAnvil->site))
        //        {
        //            //---- Initialize the Site
        //            $phpAnvil->site->close();
        //            $return = true;
        //        } else {
        //            FB::error('Site not set in phpAnvil.');
        //        }
        //
        //        $phpAnvil->triggerEvent('application.close');
        //
        //        return $return;
    }


    function authenticateUser()
    {
        //        global $phpAnvil;
        //
        //        $phpAnvil->triggerEvent('application.authenticateUser');
        //
        //        return false;
    }


    function loadConfig()
    {
        global $phpAnvil;

        //        $return = false;
        //
        //        $filePath = APP_PATH . $this->configFilename;
        //        if (file_exists($filePath))
        //        {
        //            include_once $filePath;
        //
        //            FB::info('Application config file, ' . $this->configFilename . ', loaded.');
        //
        //            $return = true;
        //        }
    }
}
