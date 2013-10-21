<?php
require_once 'anvilContainer.class.php';
require_once 'anvilLink.class.php';
require_once 'anvilLiteral.class.php';
require_once 'anvilNavDropdown.class.php';
require_once 'anvilNavItem.class.php';


/**
 * phpAnvil NavBar List Control
 *
 * @copyright     Copyright (c) 2012 Nick Slevkoff (http://www.slevkoff.com)
 */
class anvilNavBarList extends anvilContainer
{

    //---- Align ---------------------------------------------------------------
    const ALIGN_DEFAULT = 0;
    const ALIGN_LEFT  = 1;
    const ALIGN_RIGHT = 2;

    private $_alignClass = array(
        '',
        '',
        'navbar-right'
    );

    public $align = self::ALIGN_DEFAULT;


    public function __construct($id = '', $align = self::ALIGN_DEFAULT, $properties = null)
    {

        $this->enableLog();

        parent::__construct($id, $properties);

        $this->align      = $align;
    }

    public function addControl($control)
    {
        $objNavItem = new anvilNavItem('', false);
        $objNavItem->addControl($control);
        parent::addControl($objNavItem);
    }

    public function addDivider()
    {
        $this->addControl(new anvilLiteral('', '<li class="divider-vertical"></li>'));

    }

    public function addDropdown($title)
    {
        $objNavDropdown = new anvilNavDropdown('', $title);
        $this->addControl($objNavDropdown);

        return $objNavDropdown;
    }

    public function addLink($text, $url = '#', $active = false, $type = anvilLink::TYPE_DEFAULT, $size = anvilLink::SIZE_DEFAULT, $properties = null)
    {
        $objNavItem = new anvilNavItem('', $active);
        $objNavItem->addControl(new anvilLink('', $text, $url, $type, $size, $properties));
        parent::addControl($objNavItem);

        return $objNavItem;
    }

    public function renderContent()
    {

        //---- Opening Tag
        $return = '<ul';

        //---- ID
        if (!empty($this->id)) {
            $return .= ' id="' . $this->id . '"';
        }

        //---- Class
        $return .= ' class="nav navbar-nav';
        $return .= ' ' . $this->_alignClass[$this->align];
        $return .= '">' . PHP_EOL;

        $return .= $this->renderControls();

        $return .= '</ul>' . PHP_EOL;


        return $return;
    }

}
