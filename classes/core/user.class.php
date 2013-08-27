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
    public $active=0;
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
        $user->load(array('login'=>$login, 'active'=>1));
        if ($user->password == $this->_crypt->hash($password)) {
            $this->id = $user->id;
            $this->load();
            return true;
        } else {
            return false;
        }
    }

    public function sendConfirm() {
        $data = array('login'=>$this->login);
        $data['random'] = $this->_crypt->random();
        $data['code'] = $this->_crypt->hash($this->login, $data['random']);
        $body = new template('usersendConfirmBody');
        registry::getInstance()->getService('transport')->send($this->email, '', $body->fill($data));
        return $this->confirmForm($data);
    }

    public function confirmForm($data) {
        $template = new template('usersendConfirm');
        return $template->fill($data);
    }

    public function confirm($data = array()) {
        if (count($data)==0) {
            $data = registry::getInstance()->getService('request')->query;
        }
        return $data['code'] == $this->_crypt->hash($data['login'], $data['random']);
    }

    public function addAvatar($filename, $type) {
        if (!$this->id) {
            throw new Exception("User id was not specified");
        }
        $image = new image($filename, $type);
        $image->destination = $_SERVER['DOCUMENT_ROOT'].'/uploads/avatar/'.$this->id.'.'.$type;
        $image->resize(100, 100, image::CROP);
        $this->avatar = '/uploads/avatar/'.$this->id.'.'.$type;
        $this->save();
    }
 
    public function addPhoto($filename, $type) {
        if (!$this->id) {
            throw new Exception("User id was not specified");
        }
        $image = new image($filename, $type);
        $image->destination = $_SERVER['DOCUMENT_ROOT'].'/uploads/photo/'.$this->id.'.'.$type;
        $image->resize(800, 600, image::FIT);
        $this->photo = '/uploads/photo/'.$this->id.'.'.$type;
        $this->save();
    } 

}

?>
