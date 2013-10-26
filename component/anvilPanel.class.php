<?php

require_once('anvilPanelType.interface.php');
require_once('anvilContainer.class.php');


/**
 * phpAnvil Panel Container Control
 *
 * @copyright       Copyright (c) 2009-2012 Nick Slevkoff (http://www.slevkoff.com)
 */
class anvilPanel extends anvilContainer implements anvilPanelTypeInterface
{

    const VERSION = '1.0';

    public $bodyEnabled = true;

//    public $body;

    public $footer;

    public $footerEnabled = false;

    public $headerActions;

    public $headerClass;

    public $headerEnabled = true;

    public $subTitle;

    public $title;

    public $type = self::PANEL_TYPE_DEFAULT;

    private $typeClass = array(
        'panel-default',
        'panel-primary',
        'panel-success',
        'panel-info',
        'panel-warning',
        'panel-danger'
    );


    public function __construct($id = 0, $title = '', $properties = null)
    {

        parent::__construct($id, $properties);

        $this->headerActions = new anvilContainer();
//        $this->body = new anvilContainer();
        $this->footer = new anvilContainer();
        $this->title = $title;
    }


    public function renderContent()
    {

        $return = '';

        //---- Render Container Header -----------------------------------------
        $return .= $this->renderContainerHeader();

        //---- Wrapper ---------------------------------------------------------
        $return .= '<div';

        if ($this->id) {
            $return .= ' id="' . $this->id . '"';
        }

        $return .= ' class="panel';
        $return .= ' ' . $this->typeClass[$this->type];

        if (!empty($this->class)) {
            $return .= ' ' . $this->class;
        }

        $return .= '"';

        if ($this->style) {
            $return .= ' style="' . $this->style . '"';
        }

        $return .= '>';

        //---- Header ----------------------------------------------------------
        if ($this->headerEnabled) {
            $return .= $this->_renderHeader();
        }

        //---- Body ------------------------------------------------------------
        if ($this->bodyEnabled) {
            $return .= $this->_renderBody();
        }

        //---- Footer ----------------------------------------------------------
        if ($this->footerEnabled) {
            $return .= $this->_renderFooter();
        }

        $return .= '</div>';

        //---- Render Container Footer -----------------------------------------
        $return .= $this->renderContainerFooter();

        return $return;
    }


    protected function _renderBody()
    {
        $return = '<div class="panel-body">';
        $return .= $this->renderControls();
        $return .= '</div>';

        return $return;
    }


    protected function _renderFooter()
    {
        $return = '<div class="panel-footer">';
        $return .= $this->footer->renderControls();
        $return .= '</div>';

        return $return;
    }


    protected function _renderHeader()
    {
        $return = '<div class="panel-heading';
        if ($this->headerClass) {
            $return .= ' ' . $this->headerClass;
        }
        $return .= '">';

//        $return .= '<h2>';
        $return .= $this->title;
        if (!empty($this->subTitle)) {
            $return .= ' <small>' . $this->subTitle . '</small>';
        }
//        $return .= '</h2>';

        //---- Actions
        $return .= '<div class="panel-actions">';
        $return .= $this->headerActions->renderControls();
        $return .= '</div>';

        $return .= '</div>';

        return $return;
    }

}
