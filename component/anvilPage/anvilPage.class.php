<?php
/**
 * @file
 * @author          Nick Slevkoff <nick@slevkoff.com>
 * @copyright       Copyright (c) 2010 Nick Slevkoff (http://www.slevkoff.com)
 * @license
 *                  This source file is subject to the new BSD license that is
 *                  bundled with this package in the file LICENSE.txt. It is also
 *                  available on the Internet at:  http://www.phpanvil.com/LICENSE.txt
 * @ingroup         phpAnvilTools
 */


require_once PHPANVIL2_COMPONENT_PATH . 'anvilContainer.class.php';
require_once 'anvilPageHead.class.php';


/**
 * Page Container Control
 *
 * @author          Nick Slevkoff <nick@slevkoff.com>
 * @copyright       Copyright (c) 2010-2012 Nick Slevkoff (http://www.slevkoff.com)
 * @ingroup         phpAnvilTools
 */
class anvilPage extends anvilContainer
{

    const VERSION = '1.1';


    public $template;
    public $head;

    private $_preClientScript;
    private $_postClientScript;


    public function __construct($properties = null)
    {
//        $this->enableLog();

        $this->head = new anvilPageHead();

        parent::__construct(0, $properties);
    }


    public function assign($var, $value)
    {
        $this->template->assign($var, $value);
    }


    public function assignTokens()
    {
        $this->head->render();
        $this->head->html .= $this->_preClientScript;

        $this->assign('head', (array)$this->head);

        $this->assign('postClientScript', $this->_postClientScript);

    }


    public function displayControls()
    {
        //		$this->logDebug('Executing...');

        //        fb::log('anvilPage.displayControls()');
        $this->_preClientScript  = $this->renderPreClientScript();
        $this->_postClientScript = $this->renderPostClientScript();

        $return = '';
        for ($this->controls->moveFirst(); $this->controls->hasMore(); $this->controls->moveNext()) {
            $objControl = $this->controls->current();
            //			$this->logDebug('Display Control:id_' . $objControl->id);
            $this->preRenderControl($objControl);
            if ($this->innerTemplate != '' && is_object($this->template)) {

                $msg = 'Assign Control-Template:id_' . $objControl->id;
                $this->logDebug($msg);

                //        fb::log($msg);
                $html =  $objControl->render($this->template);

                $this->logDebug($html);

                $this->assign('id_' . $objControl->id, $html);
            }
        }
        return $return;
    }


    public function display()
    {
        //		$this->logDebug('Executing...');
                if (is_object($this->template)) {
        //			$this->logDebug('Set Page Template');
                    $this->template = clone $this->template;
                }

        $this->displayControls();

        $this->assignTokens();

        $this->template->display($this->innerTemplate);
    }


    public function displayPage()
    {
        $this->assignTokens();
    }


    public function render($anvilTemplate = null)
    {
        //		$this->logDebug('Executing...');

                if (is_object($this->template)) {
        //			$this->logDebug('Set Page anvilTemplate');
                    $this->template = clone $this->template;
                }
        $this->assignTokens();
        $this->displayControls();

        return $this->template->render($this->innerTemplate);
    }


    public function renderPostClientScript()
    {
        $return = '';
        for ($this->controls->moveFirst(); $this->controls->hasMore(); $this->controls->moveNext()) {
            $objControl = $this->controls->current();
            $return .= $objControl->renderPostClientScript();
        }
        return $return;
    }


    public function renderPreClientScript()
    {
        $return = '';
        for ($this->controls->moveFirst(); $this->controls->hasMore(); $this->controls->moveNext()) {
            $objControl = $this->controls->current();
            $return .= $objControl->renderPreClientScript();
        }
        return $return;
    }
}

?>