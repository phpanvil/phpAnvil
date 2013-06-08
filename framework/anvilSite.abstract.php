<?php
require_once PHPANVIL2_COMPONENT_PATH . 'anvilObject.abstract.php';


abstract class anvilSiteAbstract extends anvilObjectAbstract
{
    const ENVIRONMENT_DEVELOPMENT   = 1;
    const ENVIRONMENT_STAGING       = 2;
    const ENVIRONMENT_PRODUCTION    = 3;

    public $configFilename;

    public $environment = array();

    public $timeZone = 'UTC';
    public $path = SITE_PATH;

    public $repositoryPath;
    public $downloadPath;
    public $webPath;


	function __construct()
    {

        $this->environment['type'] = self::ENVIRONMENT_DEVELOPMENT;

		return true;
	}


    function init()
    {
        global $phpAnvil;

        $this->loadConfig();

        $phpAnvil->triggerEvent('site.init');
        return true;
    }


    function open()
    {
        global $phpAnvil;

        $phpAnvil->triggerEvent('site.open');
        return true;
    }


    function close()
    {
        global $phpAnvil;

        $phpAnvil->triggerEvent('site.close');
        return true;
    }


    function loadConfig()
    {
        $return = false;

        $filePath = APP_PATH . $this->configFilename;
        if (file_exists($filePath))
        {
            include_once $filePath;
            $return = true;
        }

        return $return;
    }


}

?>