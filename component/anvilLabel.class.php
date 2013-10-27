<?php

require_once('anvilLabelType.interface.php');

require_once('anvilControl.abstract.php');

/**
 * Inline Label Control
 *
 * @copyright     Copyright (c) 2010 Nick Slevkoff (http://www.slevkoff.com)
 */
class anvilLabel extends anvilControlAbstract implements anvilLabelTypeInterface
{
    public $typeClass = array(
        'label-default',
        'label-success',
        'label-warning',
        'label-danger',
        'label-info',
        'label-primary'
    );


    public $type;
    public $value;


    public function __construct($id = '', $value = '', $type = self::LABEL_TYPE_DEFAULT, $properties = null)
    {
//        unset($this->type);
//        unset($this->value);


//        $this->addProperty('type', self::TYPE_DEFAULT);
//        $this->addProperty('value', '');

        parent::__construct($id, $properties);

        $this->type = $type;
        $this->value = $value;
        $this->class = $this->typeClass[$this->type];

    }


    public function renderContent()
    {
        $return = '<span class="label';
        $return .= ' ' . $this->class;
        $return .= '"';

        if ($this->style) {
            $return .= ' style="' . $this->style . '"';
        }

        $return .= '>' . $this->value . '</span>';

        return $return;
    }
}
