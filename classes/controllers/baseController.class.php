<?php

class baseController extends component {

    public function getCookie() {
        return registry::getInstance()->getService('request')->cookie;
    }

    public function getQuery() {
        return registry::getInstance()->getService('request')->query;
    }

    public function getParams() {
        return registry::getInstance()->getService('request')->params;
    }

    public function Location($location) {
        header('Location: '.$location);
    }
}

?>
