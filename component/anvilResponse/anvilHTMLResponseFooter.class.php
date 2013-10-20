<?php
require_once PHPANVIL2_COMPONENT_PATH . 'anvilObject.abstract.php';

/**
 * Response Footer Class
 *
 * @copyright       Copyright (c) 2012 Nick Slevkoff (http://www.slevkoff.com)
 */
class anvilHTMLResponseFooter extends anvilObjectAbstract
{
    //---- Render Properties -------------------------------------------------
    public $html = '';
    public $prefix = "\t";
    public $scripts = '';

    public function __construct()
    {
        $this->enableLog();
        parent::__construct();
    }

    public function addScript($source = '', $script = '', $type = 'text/javascript')
    {
        $this->scripts .= '<script type="' . $type . '"';

        if (!empty($source)) {
            $this->scripts .= ' src="' . $source . '"';
        }
        $this->scripts .= '>';

        if (!empty($script)) {
            $this->scripts .=  "\n" . $script . "\n";
        }
        $this->scripts .= '</script>' . "\n";
    }


    public function render()
    {
        if (!empty($this->scripts)) {
            $this->html .= $this->scripts . "\n\n";
        }

        return $this->html;
    }
}
