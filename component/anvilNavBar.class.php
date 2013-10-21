<?php
require_once 'anvilNavBarAlign.interface.php';
require_once 'anvilNavBarType.interface.php';

require_once 'anvilContainer.class.php';

/**
 * phpAnvil NavBar Control
 *
 * @copyright     Copyright (c) 2012 Nick Slevkoff (http://www.slevkoff.com)
 */
class anvilNavBar extends anvilContainer implements anvilNavBarAlignInterface, anvilNavBarTypeInterface
{
    public $align = self::NAVBAR_ALIGN_DEFAULT;

    public $type = self::NAVBAR_TYPE_DEFAULT;

    public $collapseButton = true;
    public $collapseClass = '';
    public $containerWrapper = false;

    private $alignClass = array(
        '',
        'navbar-fixed-top',
        'navbar-fixed-bottom',
        'navbar-static-top'
    );

    private $headerControls;

    private $typeClass = array(
        'navbar-default',
        'navbar-inverse'
    );


    public function __construct($id = '', $type = self::NAVBAR_TYPE_DEFAULT, $align = self::NAVBAR_ALIGN_DEFAULT, $properties = null)
    {
        $this->enableLog();

        //---- Set Property Defaults -------------------------------------------
        $this->role = 'navigation';

        parent::__construct($id, $properties);

        $this->align = $align;
        $this->type = $type;

        $this->headerControls = new anvilContainer();
    }


    public function addHeaderControl($control)
    {
        $this->headerControls->addControl($control);
    }

    public function renderContent()
    {
        $return = '';

        //---- Opening Tag
        if ($this->htmlVersion === self::HTML_VERSION_5) {
            $return .= '<nav ';
        } else {
            $return .= '<div ';
        }

        //---- ID
        if (!empty($this->id)) {
            $return .= ' id="' . $this->id . '"';
        }

        //---- Class
        $return .= ' class="navbar ';
        $return .= ' ' . $this->typeClass[$this->type];
        $return .= ' ' . $this->alignClass[$this->align];

        if (!empty($this->class)) {
            $return .= ' ' . $this->class;
        }

        $return .= '"';

        //---- Role
        if (!empty($this->role)) {
            $return .= ' role="' . $this->role . '"';
        }

        $return .= '>' . PHP_EOL;

        //---- Start Container Wrapper -----------------------------------------
        if ($this->containerWrapper) {
            $return .= '<div class="container">' . PHP_EOL;
        }

        //---- Header Controls ---------------------------------------------------
        $return .= '<div class="navbar-header">' . PHP_EOL;

        //---- Collapse Button
        if ($this->collapseButton) {
            $return .= '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="';
            if (!empty($this->collapseClass)) {
                $return .= $this->collapseClass;
            } else {
                $return .= '.navbar-collapse';
            }
            $return .= '">' . PHP_EOL;
            $return .= '<span class="icon-bar"></span>' . PHP_EOL;
            $return .= '<span class="icon-bar"></span>' . PHP_EOL;
            $return .= '<span class="icon-bar"></span>' . PHP_EOL;
            $return .= '</button>' . PHP_EOL;
        }

        $return .= $this->headerControls->render();
        $return .= '</div>' . PHP_EOL;

        //---- Start Collapsible Content ---------------------------------------
        $return .= '<div class="navbar-collapse collapse';
        if (!empty($this->collapseClass)) {
            $return .= ' ' . $this->collapseClass;
        }
        $return .= '">' . PHP_EOL;

        //---- Render Controls -------------------------------------------------
        $return .= $this->renderControls();

        //---- End Collapsible Content -----------------------------------------
        $return .= '</div>' . PHP_EOL;


        //---- End Container Wrapper -------------------------------------------
        if ($this->containerWrapper) {
            $return .= '</div>' . PHP_EOL;
        }

        //---- Closing Tag
        if ($this->htmlVersion === self::HTML_VERSION_5) {
            $return .= '</nav>' . PHP_EOL;
        } else {
            $return .= '</div>' . PHP_EOL;
        }


        return $return;
    }

}
