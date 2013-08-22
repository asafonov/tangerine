<?php

class pageController {

    public function __construct() {
        ;
    }

    public function run() {
        $page = new page();
        $page->load(array('url' => registry::getInstance()->getService('request')->url));
        return $page->display();
    }

}

?>
