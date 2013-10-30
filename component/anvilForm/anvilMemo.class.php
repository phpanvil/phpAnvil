<?php

require_once 'anvilValidationFormControl.abstract.php';


/**
 * Multi-Lined Text Entry Control
 *
 * @copyright     Copyright (c) 2010-2012 Nick Slevkoff (http://www.slevkoff.com)
 */
class anvilMemo extends anvilValidationFormControlAbstract
{

    public $rows;
    public $value;

    public function __construct($id = '', $name = '', $rows = 3, $value = '', $properties = array())
    {

        $this->rows    = $rows;
        $this->value   = $value;

        parent::__construct($id, $name, $properties);
    }


    public function renderContent()
    {
        $return = '';

        $return .= '<textarea';

        if ($this->id) {
            $return .= ' id="' . $this->id . '"';
        }

        if ($this->name) {
            $return .= ' name="' . $this->name . '"';
        }

//        if ($this->columns) {
//            $return .= ' cols="' . $this->columns . '"';
//        }

        if ($this->rows) {
            $return .= ' rows="' . $this->rows . '"';
        }

        $return .= ' class="form-control';

        if ($this->class) {
            $return .= ' ' . $this->class;
        }
        $return .= '"';

        if ($this->style) {
            $return .= ' style="' . $this->style . '"';
        }

        //---- Render Validation -----------------------------------------------
        $return .= $this->renderValidationParameters();

        $return .= '>';

        if ($this->value) {
            $return .= $this->value;
        }

        $return .= '</textarea>';

        if ($this->validation) {
            $return .= '<span class="help-block"></span>';
        }

        return $return;
    }

}
