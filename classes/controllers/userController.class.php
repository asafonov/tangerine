<?php

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

    public function registerForm() {
        if ($this->isAuthorized()) {
            $this->Location('/');
        }
        $template = new template('userregisterForm');
        return $template->fill();
    }
}

?>
