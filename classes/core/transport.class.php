<?php

class transport extends component {

    public $from;

    public function __construct() {
        if (!$this->from) {
            $this->from = 'no-reply@'.$_SERVER['HTTP_HOST'];
        }
    }

    public function send($to, $subject, $body) {
        $headers = 'From: ' . $this->from . "\r\n" .
        'Content-Type: text/html; charset=utf-8';
        if (mail($to, $subject, $body, $headers)) {
            return true;
        } else {
            throw new RuntimeException('Unable to send confirmation');
        }
    }
}

?>
