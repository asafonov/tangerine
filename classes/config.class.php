<?php

class config {

    private static $data;

    public static getValue($name) {
        return isset(self::$data)?self::$data:false;
    }

}

?>
