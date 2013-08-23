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

    private function _loginForm() {
        $template = new template('user_loginForm');
        return $template->fill($this->query);
    }

    private function _processLogin() {
    }
}

?>
