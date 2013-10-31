<?php

require_once('anvilButtonAction.interface.php');
require_once('anvilButtonSize.interface.php');
require_once('anvilButtonType.interface.php');

require_once('anvilControl.abstract.php');


/**
 * Standard Button Control
 *
 * @copyright       Copyright (c) 2012 Nick Slevkoff (http://www.slevkoff.com)
 */
class anvilButton extends anvilControlAbstract
    implements anvilButtonActionInterface, anvilButtonSizeInterface, anvilButtonTypeInterface
{
    public $action = self::BUTTON_ACTION_DEFAULT;

    public $ariaHidden;

    public $confirmMsg;

    //---- Properties ----------------------------------------------------------

    public $dataDismiss;

    public $isActive = false;

    public $isBlock = false;

    public $isDisabled = false;

    public $name = 'btn';

    public $size = self::BUTTON_SIZE_DEFAULT;

    public $text;

    public $type = self::BUTTON_TYPE_DEFAULT;

    public $value = '';

    private $_actionText = array(
        '',
        'submit',
        'reset',
        'button',
        'image',
        'submit',
        'submit',
        'button'
    );

    private $_sizeClass = array(
        '',
        'btn-xs',
        'btn-sm',
        'btn-lg'
    );

    private $_typeClass = array(
        '',
        'btn-primary',
        'btn-info',
        'btn-success',
        'btn-warning',
        'btn-danger',
        'btn-inverse'
    );


    public function __construct($id = '', $text = 'Submit', $action = self::BUTTON_ACTION_DEFAULT, $type = self::BUTTON_TYPE_DEFAULT, $size = self::BUTTON_SIZE_DEFAULT, $properties = array())
    {
        $this->action = $action;
        $this->text = $text;
        $this->type = $type;
        $this->size = $size;

        parent::__construct($id, $properties);
    }


    public function renderContent()
    {
        $return = '<button';

        //---- ID
        if ($this->id) {
            $return .= ' id="' . $this->id . '"';
        }

//        $return .= ' type="button"';
        //---- Type
        if (!empty($this->_actionText[$this->action])) {
            $return .= ' type="';
            $return .= $this->_actionText[$this->action];
            $return .= '"';
        }

        //---- Class
        $return .= ' class="btn';
        if ($this->action != self::BUTTON_ACTION_SIMPLE) {
            $return .= ' ' . $this->_sizeClass[$this->size];
            $return .= ' ' . $this->_typeClass[$this->type];
        }

        if ($this->isBlock) {
            $return .= ' btn-block';
        }

        if ($this->action == self::BUTTON_ACTION_TOGGLE) {
            $return .= ' btn-toggle';
        }

        if ($this->class) {
            $return .= ' ' . $this->class;
        }

        if ($this->isActive) {
            $return .= ' active';
        }

        $return .= '"';

        if ($this->style) {
            $return .= ' style="' . $this->style . '"';
        }


        if ($this->name != '') {
            $return .= ' name="' . $this->name . '"';
        }

        if ($this->value != '') {
            $return .= ' value="' . $this->value . '"';
        } else {
            $return .= ' value="' . $this->text . '"';
        }

        if ($this->isDisabled) {
            $return .= ' disabled="disabled"';
        }

        if ($this->dataDismiss) {
            $return .= ' data-dismiss="' . $this->dataDismiss . '"';
        }

        if ($this->ariaHidden) {
            $return .= ' aria-hidden="' . $this->ariaHidden . '"';
        }


        if ($this->confirmMsg) {
            $return .= " onclick=\"return confirm('" . $this->confirmMsg . "');\"";
        }

        $return .= ">";

        $return .= $this->text;

        $return .= '</button>';

        return $return;
    }
}
