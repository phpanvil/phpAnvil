<?php

require_once 'anvilController.abstract.php';
require_once PHPANVIL2_COMPONENT_PATH . 'anvilContainer.class.php';
require_once PHPANVIL2_COMPONENT_PATH . 'anvilPage/anvilPage.class.php';


abstract class anvilPageControllerAbstract extends anvilControllerAbstract
{

//    public $template;
    public $page;
    public $templateFilename = 'default.tpl';
    private $_content;


	function __construct()
    {

        parent::__construct();

        $this->enableLog();

        $this->page = new anvilPage('', '', null, true);
        $this->_content = new anvilContainer('content');

		return true;
	}


    function init()
    {
//        global $phpAnvil;

        $return = parent::init();

        if ($return) {
//            $this->page = new anvilPage('', '', null, true);
        }

        return $return;
    }


    function open()
    {
        global $phpAnvil;

        $return = parent::open();

        if ($return) {
            if (!is_object($this->page->template)) {
//                $this->page->anvilTemplate = $this->template;
                $this->page->template = $phpAnvil->application->newTemplate();
//                $this->page->template = $phpAnvil->application->newTemplate();
            }
        }

        return $return;
    }


    public function addControl($control)
    {
        $this->page->addControl($control);
    }

    public function addContentControl($control)
    {
        $this->_content->addControl($control);
    }

    public function assign($var, $value)
    {
        $this->page->template->assign($var, $value);
    }

    function display()
    {
        global $phpAnvil;

        $this->page->innerTemplate = $this->templateFilename;

//        $this->assign('applicationName', $phpAnvil->application->name);
//        $this->assign('applicationRefName', $phpAnvil->application->refName);
//        $this->assign('applicationVersion', $phpAnvil->application->version);
//        $this->assign('applicationBuild', $phpAnvil->application->build);
//        $this->assign('applicationCopyright', $phpAnvil->application->copyright);

        $this->addControl($this->_content);

        $appTokens = array(
            'name'          => $phpAnvil->application->name,
            'refName'       => $phpAnvil->application->refName,
            'version'       => $phpAnvil->application->version,
            'build'         => $phpAnvil->application->build,
            'copyright'     => $phpAnvil->application->copyright,
            'copyrightHTML' => $phpAnvil->application->copyrightHTML
        );
        $this->assign('app', $appTokens);


        //---- HEAD ------------------------------------------------------------
        $this->assign('webPath', $phpAnvil->site->webPath);

        $this->page->display();
    }

}

?>