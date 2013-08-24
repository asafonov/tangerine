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
    private $_crypt;

    public function __construct() {
        parent::__construct();
        $this->_crypt = new crypt();
    }

    public function create($data = array()) {
        if (count($data)==0) {
            $data = registry::getInstance()->getService('request')->query;
        }
        $data['password'] = isset($data['password'])?$this->_crypt_hash($data['password']):'';
        $this->init($data);
        $this->save();
    }

    public function login($login, $password) {
        $user = new user();
        $user->load(array('login'=>$login));
        if ($user->password == $this->_crypt->hash($password)) {
            $this->id = $user->id;
            $this->load();
            return true;
        } else {
            return false;
        }
    }

}

?>
