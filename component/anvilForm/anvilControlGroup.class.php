<?php
require_once PHPANVIL2_COMPONENT_PATH . 'anvilContainer.class.php';


/**
 * Fieldset Control
 *
 * @copyright     Copyright (c) 2012 Nick Slevkoff (http://www.slevkoff.com)
 */
class anvilControlGroup extends anvilContainer
{

    public $label;
    public $labelForID;
    public $isRequired = false;

    public function __construct($id = '', $label = '', $labelForID = '', $properties = null)
    {

        $this->enableLog();

        parent::__construct($id, $properties);

        $this->label = $label;
        $this->labelForID = $labelForID;
    }


    public function renderContent()
    {

        $return = '<div class="form-group';
        if (!empty($this->class)) {
            $return .= ' ' . $this->class;
        }
        $return .= '">';

        if (!empty($this->label)) {
            $return .= '<label for="' . $this->labelForID . '">';

            if ($this->isRequired) {
                $return .= '<i class="fa fa-asterisk" title="required"></i>';
            }

            $return .= $this->label . '</label>';
        }

//        $return .= '<div class="controls">';

        $return .= $this->renderControls();

//        $return .= '</div>';
        $return .= '</div>';


        return $return;
    }

}
