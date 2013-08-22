<?php

class text extends activeRecord {

    private $title;
    private $body;

    public function __construct() {
        parent::construct();
    }

    public function setTitle($title) {
        $this->title = htmlspecialchars($title, ENT_QUOTES);
    }

    public function setBody($body) {
        $this->body = htmlspecialchars($body, ENT_QUOTES);
    }

}

?>
