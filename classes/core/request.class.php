<?php

class request extends component {
    
    private $data;
    private $pages;
    private $current_page;

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

    public function getQuery() {
        return array_merge($_GET, $_POST);
    }

    public function getCookie() {
        return $_COOKIE;
    }

    public function getFiles() {
        return $_FILES;
    }

    public function getURL() {
        if (isset($this->data['url'])) return $this->data['url'];
        $this->_findPage();
        return $this->data['url'];
    }

    public function getCurrentPage() {
        if ($this->current_page) return $this->current_page;
        $this->_findPage();
        return $this->current_page;
    }

    public function getParams() {
        if (isset($this->data['params'])) return $this->data['params'];
        $this->_findPage();
        return $this->data['params'];
    }

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

}
?>
