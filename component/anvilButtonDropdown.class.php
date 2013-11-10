<?php
require_once('anvilButtonType.interface.php');

require_once 'anvilContainer.class.php';
require_once 'anvilLiteral.class.php';

/**
 * phpAnvil Button Dropdown Control
 *
 * @copyright     Copyright (c) 2012 Nick Slevkoff (http://www.slevkoff.com)
 */
class anvilButtonDropdown extends anvilContainer
    implements anvilButtonTypeInterface
{

    public $title;

    public $buttonClass;
    public $dropdownClass;

    public $isSplit = false;

    public $type = self::BUTTON_TYPE_NORMAL;

    private $_typeClass = array(
        'btn-default',
        'btn-primary',
        'btn-info',
        'btn-success',
        'btn-warning',
        'btn-danger',
        'btn-inverse'
    );


    public function __construct($id = '', $title = '', $type = self::BUTTON_TYPE_NORMAL, $properties = null)
    {

        $this->enableLog();

        parent::__construct($id, $properties);

        $this->title      = $title;
        $this->type = $type;
    }

    public function addDivider()
    {
        $this->addControl(new anvilLiteral('', '<li class="divider"></li>'));

    }

    public function addLink($text, $url = '', $active = false, $properties = null)
    {
        $objNavItem = new anvilNavItem('', $active);
        $objNavItem->addControl(new anvilLink('', $text, $url, anvilLink::TYPE_DEFAULT, anvilLink::SIZE_DEFAULT, $properties));
        $this->addControl($objNavItem);

        return $objNavItem;
    }


    public function addStatusLink($text, $url, $status)
    {
        $icon = '<i class="icon-';
        if ($status) {
            $icon .= 'ok';
        } else {
            $icon .= 'none';
        }
        $icon .= '"></i>&nbsp';

        $return = $this->addLink($icon . $text, $url);
        return $return;
    }


    public function renderContent()
    {

        $return = '<div class="btn-group';
        if (!empty($this->class)) {
            $return .= ' ' . $this->class;
        }
        $return .= '">';

        if ($this->isSplit) {
            $return .= '<button class="btn';

            if (!empty($this->buttonClass)) {
                $return .= ' ' . $this->buttonClass;
            }

            $return .= '">';
            $return .= $this->title;
            $return .= '</button>';

            $return .= '<button class="btn';

            if (!empty($this->buttonClass)) {
                $return .= ' ' . $this->buttonClass;
            }

            $return .= ' dropdown-toggle" data-toggle="dropdown">';
            $return .= '<span class="caret"></span>';
            $return .= '</button>';

        } else {
            //---- Render Button Link ----------------------------------------------
            $return .= '<button type="button" class="btn';

            $return .= ' ' . $this->_typeClass[$this->type];

            if (!empty($this->buttonClass)) {
                $return .= ' ' . $this->buttonClass;
            }

            $return .= ' dropdown-toggle" data-toggle="dropdown">';
            $return .= $this->title;
            $return .= ' <span class="caret"></span>';
            $return .= '</button>';
        }


        $return .= '<ul class="dropdown-menu';
        if (!empty($this->dropdownClass)) {
            $return .= ' ' . $this->dropdownClass;
        }
        $return .= '" role="menu">';

        $return .= $this->renderControls();

        $return .= '</ul>';
        $return .= '</div>';


        return $return;
    }

}
