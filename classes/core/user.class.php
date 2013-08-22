<?php

class user extends activeRecord {

    private $login;
    private $password;
    private $email;
    private $name;
    private $sex;
    private $avatar;
    private $photo;
    private $country;
    private $city;

    public function __construct() {
    }

    public function create($data = array()) {
        if (count($data)==0) {
            $data = registry::getInstance()->getService('request')->query;
        }
        $this->init($data);
        $this->save();
    }

}

?>
