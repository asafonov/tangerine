<?php

/**
 * The class provides functionality to work with user request
 * It parses requested url and try to determine requested page, parameters
 * and additional information
 *
 * @author Alexander Safonov <me@asafonov.org>
 */
class request extends component {
    
    /**
     * Class data
     * @var array
     */
    private $data;
    /**
     * Array of pages
     * @var array
     */
    private $pages;
    /**
     * ID of the current page
     * @var string
     */
    private $current_page;

    /**
     * Populate class data from the requested url. Parse page data and parameters
      */
    private function _findPage() {
        $url = str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
        for ($i=0, $j=count($this->pages); $i<$j; $i++) {
            if (preg_match('/^\/{0,1}'.str_replace('/', '\/', $this->pages[$i]['url']).'\/{0,1}$/i', $url, $matches)) {
                if (isset($matches[1])) {
                    $this->current_page = $this->pages[$i]['id'];
                    $this->data['url'] = $matches[1];
                    $this->data['params'] = explode('/', str_replace($matches[1], '', $matches[0]));
                    if ($this->data['params'][count($this->data['params'])-1]=='') {
                        unset($this->data['params'][count($this->data['params'])-1]);
                    }
                    unset($this->data['params'][0]);
                    $this->data['params'] = array_values($this->data['params']);
                } else {
                    $this->data['url'] = $matches[0];
                    $this->current_page = $this->pages[$i]['id'];
                    $this->data['params'] = array();
                }
                break;
            }
        }
    }

    /**
     * Constructor
     */
    public function __construct() {
        $list = new activeList('page');
        $this->pages = $list->asArray();       
        if (get_magic_quotes_gpc()) {
            $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
            while (list($key, $val) = each($process)) {
                foreach ($val as $k => $v) {
                    unset($process[$key][$k]);
                    if (is_array($v)) {
                        $process[$key][stripslashes($k)] = $v;
                        $process[] = &$process[$key][stripslashes($k)];
                    } else {
                        $process[$key][stripslashes($k)] = stripslashes($v);
                    }
                }
            }
            unset($process);
        }
    }

    /**
     * Get Cookie
     */
    public function getCookie() {
        return $_COOKIE;
    }

    /**
     * Get ID of the current page
     */
    public function getCurrentPage() {
        if ($this->current_page) return $this->current_page;
        $this->_findPage();
        return $this->current_page;
    }

    /**
     * Get uploaded files
     */
    public function getFiles() {
        return $_FILES;
    }

    /**
     * Get page paramaters
     */
    public function getParams() {
        if (isset($this->data['params'])) return $this->data['params'];
        $this->_findPage();
        return $this->data['params'];
    }

    /**
     * Get query
     */
    public function getQuery() {
        return array_merge($_GET, $_POST);
    }

    /**
     * Get URL of the page
     */
    public function getURL() {
        if (isset($this->data['url'])) return $this->data['url'];
        $this->_findPage();
        return $this->data['url'];
    }

}
?>
