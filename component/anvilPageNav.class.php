<?php
require_once('anvilControl.abstract.php');


/**
 * Page Control
 *
 * @copyright      Copyright (c) 2010-2011 Nick Slevkoff ({@link http://www.slevkoff.com})
 * @license        http://www.phpanvil.com/LICENSE.txt		New BSD License
 * @version        1.0
 * @ingroup        phpAnvilTools
 */


class anvilPageNav extends anvilControlAbstract
{

//    const NAME                = 'anvilPageNav';
//    const VERSION            = '3.0';
//    const VERSION_BUILD     = '8';
//    const VERSION_DTS        = '4/6/2009 8:45:00 PM PST';
//    const COPYRIGHT            = 'Copyright (c) 2009 by Devuture, Inc.';


    public $currentPage = 1;

    public $imageFirst = 'bPageNavFirst.png';

    public $imageLast = 'bPageNavLast.png';

//    public $htmlID = 'pageNav';

//    public $mainClass;

//    public $maxRows = 25;
//    public $maxNavPages = 7;

    public $imageNext = 'bPageNavNext.png';

    public $imagePath = 'images/';

    public $imagePrev = 'bPageNavPrev.png';

    public $itemOffset = 0;

    public $itemsPerPage = 25;

    public $maxNavPages = 5;

    public $pagePath = '';

    public $phraseFirst = '<i class="fa fa-step-backward"></i>';

    public $phraseLast = '<i class="fa fa-step-forward"></i>';

    public $phraseNext = '<i class="fa fa-chevron-right"></i>';

    public $phrasePrev = '<i class="fa fa-chevron-left"></i>';

    public $qsPrefix = 'pn_';

    public $showPageCount = false;

    public $totalItems = 0;

    public $totalItemsName = 'Rows';

    public $totalPages = 1;


    public function __construct($id = '', $class = '', $qsPrefix = 'pn_', $properties = null)
    {
        $this->enableLog();

        parent::__construct($id, $properties);

        $this->class = $class;
        $this->qsPrefix = $qsPrefix;

        $this->imagePath = $this->getBasePath() . '/images/';

        #---- Auto Detect Current Page
        $sessionKey = '';
        if ($id == 'pageNav') {
            $this->currentPage = isset($_GET[$this->qsPrefix . 'pg'])
                    ? intval($_GET[$this->qsPrefix . 'pg'])
                    : 1;
        } else {
            $sessionKey = 'pageNav.current.' . $this->id;
            $this->currentPage = (isset($_GET[$this->qsPrefix . 'pg'])
                    ? intval($_GET[$this->qsPrefix . 'pg'])
                    : (isset($_SESSION[$sessionKey])
                            ? intval($_SESSION[$sessionKey])
                            : 1));
        }

        if ((int)$this->currentPage < 2) {
            $this->itemOffset = 0;
            $this->currentPage = 1;
        } else {
            $this->itemOffset = ((int)$this->currentPage - 1) * (int)$this->itemsPerPage;
        }

        if ($id != 'pageNav') {
            $_SESSION[$sessionKey] = $this->currentPage;
        }
    }


    public function __set($propertyName, $value)
    {
        $return = parent::__set($propertyName, $value);

        switch ($propertyName) {
            case 'totalItems':
            case 'totalPages':
            case 'itemsPerPage':

                $this->totalPages = ceil((int)$this->totalItems / (int)$this->itemsPerPage);
//                fb::log('$this->totalPages = ' . $this->totalPages);

                break;

            case 'itemOffset':
            case 'currentPage':

                if ((int)$this->currentPage < 2) {
                    $this->itemOffset = 0;
                    $this->currentPage = 1;
                } else {
                    $this->itemOffset = ((int)$this->currentPage - 1) * (int)$this->itemsPerPage;
                }

//                fb::log('$this->itemOffset = ' . $this->itemOffset);

                break;
        }

        return $return;
    }


    function addQSVar($url, $key, $value)
    {
        $url = $this->removeQSVar($url, $key);
        if (strpos($url, '?') === false) {
            return ($url . '?' . $key . '=' . $value);
        } else {
            return ($url . '&' . $key . '=' . $value);
        }
    }


    public function getBasePath()
    {
        $path = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on')
                ? 'https://'
                : 'http://';
        $path .= $_SERVER["SERVER_NAME"];
        if ($_SERVER["SERVER_PORT"] != '80' && $_SERVER["SERVER_PORT"] != '443') {
            $path .= ':' . $_SERVER["SERVER_PORT"];
        }

        return $path;
    }


    public function getPagePath()
    {
        $return = $this->pagePath;

        if (empty($return)) {
            $return = $this->getBasePath();
            $return .= $_SERVER["REQUEST_URI"];

//        $this->_logDebug($return, '$return 1');
//        $this->_logDebug($this->qsPrefix . 'pg', 'QS');

            $return = $this->removeQSVar($return, $this->qsPrefix . 'pg');
//        $this->_logDebug($return, '$return 2');
        }

        return $return;
    }


    function removeQSVar($url, $key)
    {
//        $this->_logDebug('/(?:&|(\?))' . $key . '=[^&]*(?(1)&|)?/i', 'RegEx');

        $url = preg_replace('/(?:&|(\?))' . $key . '=[^&]*(?(1)&|)?/i', '$1$2$4', $url . '&');
        $url = substr($url, 0, -1);

        return $url;
    }


    public function render($devTemplate = null)
    {

        #----- Build URL Strings
        $baseURL = $this->getPagePath();



        $this->totalPages = ceil((int)$this->totalItems / (int)$this->itemsPerPage);

        if ((int)$this->currentPage < 2) {
            $this->itemOffset = 0;
            $this->currentPage = 1;
        } else {
            $this->itemOffset = ((int)$this->currentPage - 1) * (int)$this->itemsPerPage;
        }

        #---- Render Page Navigation HTML
        $pagesPerSide = ($this->maxNavPages - 1) / 2;

//        $this->_logDebug($pagesPerSide, '$pagesPerSide');

        $html = '<ul';
        if (!empty($this->id)) {
            $html .= ' id="' . $this->id . '"';
        }

        $html .= ' class="pagination';

        if (!empty($this->class)) {
            $html .= ' ' . $this->class;
        }
        $html .= '">';

//        $this->_logDebug($this->totalPages, '$this->totalPages');


        if ($this->totalPages <= 1) {
            $html .= '<li class="pages">' . $this->totalItems . '&nbsp;' . $this->totalItemsName . '</li>';
        } else {
            $html .= '<li class="pages"><a href="#" class="hidden-xs">' . $this->totalItems;
            $html .= ' ' . $this->totalItemsName;

            if ($this->showPageCount) {
                $html .= ', ' . $this->totalPages . ' Pages';
            }
            $html .= '</a></li>';

            if ($this->totalPages >= ($this->maxNavPages * 2)) {
                if ($this->currentPage == 1) {
                } else {
                    $url = $this->addQSVar($baseURL, $this->qsPrefix . 'pg', 1);
                    $html .= '<li class="first"><a href="' . htmlentities($url) . '">' . $this->phraseFirst . '</a></li>';
                }
            }

            if ($this->currentPage == 1) {
            } else {
                $url = $this->addQSVar($baseURL, $this->qsPrefix . 'pg', $this->currentPage - 1);
                $html .= '<li class="prev"><a href="' . htmlentities($url) . '" class="nextPrev">' . $this->phrasePrev . '</a></li>';
            }

            if (($this->currentPage - 1000) > 1) {
                $url = $this->addQSVar($baseURL, $this->qsPrefix . 'pg', $this->currentPage - 1000);
                $html .= '<li><a href="' . htmlentities($url) . '">' . ($this->currentPage - 1000) . '...</a></li>';
            }

            if (($this->currentPage - 100) > 1) {
                $url = $this->addQSVar($baseURL, $this->qsPrefix . 'pg', $this->currentPage - 100);
                $html .= '<li><a href="' . htmlentities($url) . '">' . ($this->currentPage - 100) . '...</a></li>';
            }

            if (($this->currentPage - 10) > 1) {
                $url = $this->addQSVar($baseURL, $this->qsPrefix . 'pg', $this->currentPage - 10);
                $html .= '<li><a href="' . htmlentities($url) . '">' . ($this->currentPage - 10) . '...</a></li>';
            }


            $firstNavPage = $this->currentPage - $pagesPerSide;
            if ($firstNavPage < 1) {
                $firstNavPage = 1;
            }

            if ($this->totalPages > $this->maxNavPages) {
                $totalNavPages = $firstNavPage + ($this->maxNavPages - 1);

                if ($totalNavPages > $this->totalPages) {
                    $totalNavPages = $this->totalPages;
                    $firstNavPage = $totalNavPages - ($this->maxNavPages - 1);
                }
            } else {
                $totalNavPages = $this->totalPages;
                $firstNavPage = 1;
            }


            for ($i = $firstNavPage; $i <= $totalNavPages; $i++) {
                if ($i == $this->currentPage) {
                    $html .= '<li class="active"><a class="active" href="#">' . $i . '</a></li>';
                } else {
                    $url = $this->addQSVar($baseURL, $this->qsPrefix . 'pg', $i);
                    $html .= '<li><a href="' . htmlentities($url) . '" class="hidden-xs"';

//                    if ($i == $this->currentPage) {
//                        $html .= ' class="active"';
//                    }
                    $html .= '>' . $i . '</a>';
                    $html .= '</li>';
                }
            }

            if (($this->currentPage + 10) < $this->totalPages) {
                $url = $this->addQSVar($baseURL, $this->qsPrefix . 'pg', $this->currentPage + 10);
                $html .= '<li><a href="' . htmlentities($url) . '">...' . ($this->currentPage + 10) . '</a></li>';
            }

            if (($this->currentPage + 100) < $this->totalPages) {
                $url = $this->addQSVar($baseURL, $this->qsPrefix . 'pg', $this->currentPage + 100);
                $html .= '<li><a href="' . htmlentities($url) . '">...' . ($this->currentPage + 100) . '</a></li>';
            }

            if (($this->currentPage + 1000) < $this->totalPages) {
                $url = $this->addQSVar($baseURL, $this->qsPrefix . 'pg', $this->currentPage + 1000);
                $html .= '<li><a href="' . htmlentities($url) . '">...' . ($this->currentPage + 1000) . '</a></li>';
            }

            if ($this->currentPage == $this->totalPages) {
            } else {
                $url = $this->addQSVar($baseURL, $this->qsPrefix . 'pg', $this->currentPage + 1);
                $html .= '<li class="next"><a href="' . htmlentities($url) . '">' . $this->phraseNext . '</a></li>';
            }

            if ($this->totalPages >= ($this->maxNavPages * 2)) {
                if ($this->currentPage == $this->totalPages) {
                } else {
                    $url = $this->addQSVar($baseURL, $this->qsPrefix . 'pg', $this->totalPages);
                    $html .= '<li class="last"><a href="' . htmlentities($url) . '">' . $this->phraseLast . '</a></li>';
                }
            }
        }
        $html .= '</ul>';

        return $html;

    }


    public function renderHTML($devTemplate = null)
    {
        return $this->render($devTemplate);
    }


    public function reset()
    {
        $sessionKey = 'pageNav.current.' . $this->id;
        $_SESSION[$sessionKey] = 1;

        $this->itemOffset = 0;
        $this->currentPage = 1;
    }

}
