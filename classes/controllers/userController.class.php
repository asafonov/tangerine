<?php

class userController extends baseController {

    public function isAuthorized() {
        $auth = new auth();
        return $auth->checkSign();
    }

    public function login() {
        if (!$this->isAuthorized()) {
            return $this->_loginForm();
        }
    }

    private function _loginForm() {
        $template = new template('user_loginForm');
        return $template->fill($this->query);
    }
}

?>
