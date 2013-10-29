<?php

require_once('anvilAlertType.interface.php');

require_once('anvilAlertMessage.class.php');

require_once('anvilContainer.class.php');


/**
 * phpAnvil Alert Container Control
 *
 * @copyright       Copyright (c) 2009-2012 Nick Slevkoff (http://www.slevkoff.com)
 */
class anvilAlert extends anvilContainer implements anvilAlertTypeInterface
{

    public $key = 'app.alert';


    public function __construct($id = 0, $properties = null)
    {
        parent::__construct($id, $properties);

        $this->enableLog();
    }


    public function add($content, $type = self::ALERT_TYPE_DEFAULT, $title = '')
    {
        $index = 0;

        if (array_key_exists($this->key, $_SESSION)) {
            $index = count($_SESSION[$this->key]);
        }

        $_SESSION[$this->key][$index]['type'] = $type;

        if (!empty($title)) {
            $_SESSION[$this->key][$index]['title'] = $title;
        }

        $_SESSION[$this->key][$index]['content'] = $content;

        return $index;
    }


    public function addButton($index, $text, $url, $class = 'btn btn-default')
    {
        $buttonIndex = 0;

        if (array_key_exists($this->key, $_SESSION) && array_key_exists($index, $_SESSION[$this->key])) {
            if (array_key_exists('buttons', $_SESSION[$this->key][$index])) {
                $buttonIndex = count($_SESSION[$this->key][$index]['buttons']);
            }

            $_SESSION[$this->key][$index]['buttons'][$buttonIndex]['text'] = $text;
            $_SESSION[$this->key][$index]['buttons'][$buttonIndex]['url'] = $url;
            $_SESSION[$this->key][$index]['buttons'][$buttonIndex]['class'] = $class;
        }
    }


    public function renderContent()
    {

        $return = '';

        if (!empty($_SESSION[$this->key])) {
            $this->_logDebug($_SESSION[$this->key]);

            $alerts = $_SESSION[$this->key];

            foreach ($alerts as $index => $alert) {

                $alertMessage = new anvilAlertMessage($alert['content'], $alert['type']);

                if (array_key_exists('title', $alert)) {
                    $alertMessage->title = $alert['title'];
                }

                $this->addControl($alertMessage);
            }

            $_SESSION[$this->key] = '';
        }

        $return .= $this->renderControls();

        return $return;
    }
}
