<?php

class user extends activeRecord {

    public $login;
    public $password;
    public $email;
    public $name;
    public $sex;
    public $avatar;
    public $photo;
    public $country;
    public $city;
    private $_crypt;

    public function __construct() {
        parent::__construct();
        $this->_crypt = new crypt();
    }

    public function create($data = array()) {
        if (count($data)==0) {
            $data = registry::getInstance()->getService('request')->query;
        }
        $data['password'] = isset($data['password'])?$this->_crypt->hash($data['password']):'';
        if (!$this->id) {
            $this->_checkEmail($data['email']);
            $this->_checkLogin($data['login']);
        }
        $this->init($data);
        $this->save();
    }

    private function _checkLogin($login) {
        $user = new user();
        $user->load(array('login'=>$login));
        if ($user->id) {
            throw new RuntimeException("Login already registered", 1);
        }
    }

    private function _checkEmail($emailk) {
        $user = new user();
        $user->load(array('email'=>$email));
        if ($user->id) {
            throw new RuntimeException("Email already registered", 2);
        }
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
