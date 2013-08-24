<?php

class userController extends baseController {

    public function isAuthorized() {
        $auth = new auth();
        return $auth->checkSign();
    }

    public function login() {
        if (!$this->isAuthorized()) {
            if (count($this->query)>0) {
                return $this->_processLogin();
            } else {
                return $this->_loginForm();
            }
        }
    }

    private function _loginForm($error=false) {
        $template = new template('user_loginForm');
        return $template->fill(array_merge($this->query, array('error'=>$error)));
    }

    private function _processLogin() {
        if (registry::getInstance()->getService('user')->login($this->query['login'], $this->query['password'])) {
            return $this->privateMode();
        } else {
            return $this->_loginForm(true);
        }
    }
}

?>