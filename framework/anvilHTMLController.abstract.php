<?php

require_once 'anvilController.abstract.php';
require_once PHPANVIL2_COMPONENT_PATH . 'anvilAlert.class.php';
require_once PHPANVIL2_COMPONENT_PATH . 'anvilContainer.class.php';
require_once PHPANVIL2_COMPONENT_PATH . 'anvilResponse/anvilHTMLResponseFooter.class.php';
require_once PHPANVIL2_COMPONENT_PATH . 'anvilResponse/anvilHTMLResponseHead.class.php';

/**
 * @property anvilContainer $response
 */
abstract class anvilHTMLControllerAbstract extends anvilControllerAbstract
{

    private $_breadcrumbTitle = array();
    private $_breadcrumbURL = array();
    public $breadcrumbDivider = '/';

    /**
     * @var anvilTemplateAbstract
     */
    protected $_template;

    /**
     * @var string
     */
    protected $_templateFilename;

    /**
     * @var anvilHTMLResponseFooter
     */
    protected $footer;

    /**
     * @var anvilHTMLResponseHead
     */
    protected $_head;

    private $_preClientScript;
    private $_postClientScript;

    protected $_tokenArray = array();

    protected $_webPath;


    function __construct()
    {
        parent::__construct();

//        $this->enableLog();

        $this->_template = $this->_application->newTemplate();
        $this->footer = new anvilHTMLResponseFooter();
        $this->_head   = new anvilHTMLResponseHead();

        $this->response = new anvilContainer();

        //---- Set Initial Tokens ----------------------------------------------
        $appTokens = array(
            'name'          => $this->_application->name,
            'refName'       => $this->_application->refName,
            'version'       => $this->_application->version,
            'build'         => $this->_application->build,
            'copyright'     => $this->_application->copyright,
            'copyrightHTML' => $this->_application->copyrightHTML
        );
        $this->_tokenArray['app'] = $appTokens;

        $this->_tokenArray['webPath'] = $this->_site->webPath;
//        $this->_webPath = $this->_site->webPath;

        $this->_tokenArray['html']['body'] = array();
        $this->_tokenArray['html']['body']['class'] = '';

        return true;
	}


    function init()
    {
        $return = parent::init();

        return $return;
    }


    function open()
    {
        $return = parent::open();

        return $return;
    }


    protected function _addBreadcrumb($title, $url)
    {
        $this->_breadcrumbTitle[] = $title;
        $this->_breadcrumbURL[]   = $url;

        return true;
    }


    protected function _addControl($control)
    {
        $this->response->addControl($control);
    }


    protected function _assign($var, $value)
    {
        $this->_template->assign($var, $value);
    }


    protected function _assignTokens()
    {
        global $phpAnvil;

        //---- Footer Tokens ---------------------------------------------------
        $this->footer->render();
        $this->footer->rendered .= $this->_postClientScript;

        $this->_tokenArray['html']['footer'] = (array)$this->footer;

        //---- HEAD Tokens -----------------------------------------------------
        $this->_head->render();
        $this->_head->rendered .= $this->_preClientScript;

        $this->_tokenArray['html']['head'] = (array)$this->_head;

        //---- Prepare Breadcrumbs ---------------------------------------------
        $count = count($this->_breadcrumbTitle);

        $html = '';

        if ($count > 0) {
            $html .= '<ul class="breadcrumb">';

            for ($i = 0; $i < $count; $i++) {
                $html .= '<li>';

                if (!empty($this->_breadcrumbURL[$i])) {
                    if (strpos($this->_breadcrumbURL[$i], 'http') === false) {
                        $html .= '<a href="' . $phpAnvil->site->webPath . $this->_breadcrumbURL[$i] . '">';
                    } else {
                        $html .= '<a href="' . $this->_breadcrumbURL[$i] . '">';
                    }
                }
                $html .= $this->_breadcrumbTitle[$i];
                if (!empty($this->_breadcrumbURL[$i])) {
                    $html .= '</a>';
                }
                $html .= ' <span class="divider">' . $this->breadcrumbDivider . '</span>';
                $html .= '</li>';
            }
            $html .= '</ul>';
        }

        $this->_tokenArray['page']['breadcrumbs'] = $html;


        //---- Assign Tokens to Template ---------------------------------------

        $tokenKeys = array_keys($this->_tokenArray);
        $count = count($tokenKeys);

        for ($i=0; $i < $count; $i++) {
            $this->_assign($tokenKeys[$i], $this->_tokenArray[$tokenKeys[$i]]);
        }
    }



    protected function _display()
    {
        //---- HEAD ------------------------------------------------------------

//        $alerts = $this->_renderAlerts();
//        $this->_tokenArray['app']['alerts'] = $alerts;


        if (is_object($this->_template)) {
            $this->_logVerbose('Cloning template for use...');
            $this->_template = clone $this->_template;
        }

        $this->_logVerbose('Assigning tokens...');
        $this->_assignTokens();

        $this->_logVerbose('Rendering controls...');
        $this->_displayControls();

        $this->_logVerbose('Rendering complete, template engine has the wheel...');

        return $this->_template->display($this->_templateFilename);

    }


    protected function _displayControls()
    {
        $this->_preClientScript  = $this->response->renderPreClientScript();
        $this->_postClientScript = $this->response->renderPostClientScript();

        $return = '';
        for ($this->response->controls->moveFirst(); $this->response->controls->hasMore(); $this->response->controls->moveNext()) {
            $objControl = $this->response->controls->current();
            //			$this->_logDebug('Display Control:id_' . $objControl->id);
            $this->response->preRenderControl($objControl);
            if ($this->_templateFilename && is_object($this->_template)) {

                $msg = 'Assign Control-Template:id_' . $objControl->id;
                $html = $objControl->render($this->_template);

                $this->_assign('id_' . $objControl->id, $html);
            }
        }
        return $return;
    }

}
