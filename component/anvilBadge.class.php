<?php

require_once('anvilControl.abstract.php');

/**
 * Inline Label Control
 *
 * @copyright     Copyright (c) 2010 Nick Slevkoff (http://www.slevkoff.com)
 */
class anvilBadge extends anvilControlAbstract
{
    public $value;

    public function __construct($id = '', $value = '', $properties = null)
    {
        parent::__construct($id, $properties);

        $this->value = $value;
    }


    public function renderContent()
    {
        $return = '<span class="badge';

        if (!empty($this->class)) {
            $return .= ' ' . $this->class;
        }

        $return .= '"';

        if (!empty($this->style)) {
            $return .= ' style="' . $this->style . '"';
        }

        $return .= '>' . $this->value . '</span>';

        return $return;
    }
}
