<?php

class request extends component {
    
    private $data;
    private $pages;

    public function __construct() {
        $list = new activeList('page');
        $pages = $list->asArray();
        for ($i=0, $j=count($pages); $i<$j; $i++) {
            $this->pages[$pages[$i]['url']] = 1;
        }
    }

    public function getQuery() {
        return $_REQUEST;
    }

    public function getCookie() {
        return $_COOKIE;
    }

    public function getURL() {
        if (isset($this->data['url'])) return $this->data['url'];
        $this->_findPage();
        return $this->data['url'];
    }

    public function getParams() {
        if (isset($this->data['params'])) return $this->data['params'];
        $this->_findPage();
        return $this->data['params'];
    }

    private function _findPage() {
        $this->data['params'] = array();
        $chunks = explode('/', $_SERVER['PHP_SELF']);
        while (count($chunks)>0) {
            $url = '/'.implode($chunks);
            if (isset($this->pages[$url])) {
                $this->data['url'] = $url;
                break;
            } else {
                $this->data['params'][] = $chunks[count($chunks)-1];
                unset($chunks[count($chunks)-1]);
            }
        }
        $this->data['params'] = array_reverse($this->data['params']);
    }

}
?>
