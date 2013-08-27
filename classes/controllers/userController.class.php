<?php

session_start();

class userController extends baseController {

    public function isAuthorized() {
        $auth = new auth();
        return $auth->checkSign();
    }

    public function login() {
        if (!$this->isAuthorized()) {
            if (count($this->query)>0&&isset($this->query['login'])&&isset($this->query['password'])) {
                return $this->_processLogin();
            } else {
                return $this->_loginForm();
            }
        } elseif (isset($this->query['logout'])) {
            return $this->_logout();
        } else {
            return $this->_privateMode();
        }
    }

    private function _loginForm($error=false) {
        $template = new template('user_loginForm');
        return $template->fill(array_merge($this->query, array('error'=>$error)));
    }

    private function _processLogin() {
        if (registry::getInstance()->getService('user')->login($this->query['login'], $this->query['password'])) {
            $auth = new auth();
            $auth->setSign();
            return $this->_privateMode();
        } else {
            return $this->_loginForm(true);
        }
    }

    private function _privateMode() {
        $template = new template('user_privateMode');
        return $template->fill(registry::getInstance()->getService('user')->asArray());
    }

    private function _logout() {
        $auth = new auth();
        $auth->deleteSign();
        return $this->_loginForm();
    }

    public function register() {
        if ($this->isAuthorized()) {
            $this->Location('/');
        }
        if (count($this->query)>0) {
            return $this->_processRegister();
        } else {
            return $this->_registerForm();
        }
    }

    private function _registerForm($data = array()) {
        $template = new template('user_registerForm');
        $captcha_array = array('session_name'=>session_name(), 'session_id'=>session_id());
        return $template->fill(array_merge($data, $captcha_array, $this->query));
    }

    private function _processRegister() {
        try {
            $this->_checkQuery();
            registry::getInstance()->getService('user')->create();
        } catch(Exception $e) {
            return $this->_registerForm(array('error'.$e->getCode()=>true));
        }
    }

    private function _checkQuery() {
        $this->_checkEmpty();
        $this->_checkPassword();
        $this->_checkCaptcha();
    }

    private function _checkEmpty() {
        if (!$this->query['login']==''&&
            !$this->query['email']==''&&
            !$this->query['password']=='') {
            return true;
        } else {
            throw new Exception("Obligatory field is not filled", 4);
        }
    }

    private function _checkPassword() {
        if ($this->query['password']==$this->query['rpassword']) {
            return true;
        } else {
            throw new Exception("Failed to repeat the password", 5);
        }
    }

    private function _checkCaptcha() {
        if (isset($_SESSION['captcha_keystring'])&&$_SESSION['captcha_keystring']==$this->query['keystring']) {
            return true;
        } else {
            throw new Exception("Error in captcha keystring", 3);
        }
    }
}

?>
