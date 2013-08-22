<?php

class config {

    private static $data = array(
//        'mysql_host'=>'localhost',
//        'mysql_login'=>'login',
//        'mysql_password'=>'password',
//        'mysql_database'=>'database',
    );

    public static function getValue($name) {
        return isset(self::$data[$name])?self::$data[$name]:false;
    }

}

?>
