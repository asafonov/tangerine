<?php

class config {

    private static $data;

    public static function getValue($name) {
        return isset(self::$data[$name])?self::$data[$name]:false;
    }

}

?>
