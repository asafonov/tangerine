<?php

class pageController extends baseController {

    public function run() {
        $page = new page();
        $page->load(array('url' => registry::getInstance()->getService('request')->url));
        return $page->display();
    }

}

?>
