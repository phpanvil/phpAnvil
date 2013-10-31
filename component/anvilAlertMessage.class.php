<?php

require_once 'anvilAlertType.interface.php';

//require_once 'anvilControl.abstract.php';

require_once('anvilContainer.class.php');


class anvilAlertMessage extends anvilContainer implements anvilAlertTypeInterface
{

    private $typeClass = array(
        '',
        'alert-success',
        'alert-warning',
        'alert-danger',
        'alert-info'
    );

    public $title;
    public $type = self::ALERT_TYPE_DEFAULT;
    public $content;
    public $block = false;
    public $dismissable = true;
    public $fade = true;
//    public $iconClass;


    public function __construct($content, $type = self::ALERT_TYPE_DEFAULT, $title = '', $properties = null)
    {

        parent::__construct(0, $properties);

        $this->type = $type;
        $this->title = $title;
        $this->content = $content;
    }


    public function renderContent()
    {

        //---- Opening Tag
        $return = '<div';

        //---- Class
        $return .= ' class="alert';
        
        $return .= ' ' . $this->typeClass[$this->type];
        
        if ($this->block) {
            $return .= ' alert-block';
        }

        if ($this->dismissable) {
            $return .= ' alert-dismissable';
        }

        if ($this->fade) {
            $return .= ' fade in';
        }

        if (!empty($this->class)) {
            $return .= ' ' . $this->class;
        }

        $return .= '"';

        //---- Style
        if ($this->style) {
            $return .= ' style="' . $this->style . '"';
        }

        $return .= '>';
        
        //---- Dismissable Button
        if ($this->dismissable) {
            $return .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        }

//        if (!empty($this->iconClass)) {
//            $return .= '<div class="alert-icon"><i class="' . $this->iconClass . '"></i></div>';
//        }

        //---- Title
        if (!empty($this->title)) {
            $return .= '<h4>' . $this->title . '</h4>';
        }

        //---- Message
        $return .= '<p>' . $this->content . '</p>';

        $return .= $this->renderControls();

        $return .= '</div>';

        return $return;
    }
}
