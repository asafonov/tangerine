<?php

class adminController extends baseController {

    public function run() {
        if (!$this->isAuthorized()) {
            return $this->login();
        } elseif (isset($this->params[0])) {
        } else {
            return $this->_hello();
        } 
    }

    public function menu() {
        if (!$this->isAuthorized()) {
            return false;
        }
        return $this->_buildMenu(registry::getInstance()->getService('user')->role);
    }

    private function _buildMenu($role) {
        $template = new template('admin/menu'.intval($role));
        return $template->fill();
    }

    public function login() {
        if (count($this->query)>1) {
            return $this->_processLogin();
        }
        $template = new template('admin/login');
        return $template->fill($this->query);
    }

    private function _hello() {
        $template = new template('admin/_hello');
        return $template->fill();
    }

    private function _processLogin() {
        if (registry::getInstance()->getService('user')->login($this->query['login'], $this->query['password'])) {
            $auth = new auth();
            $auth->setSign();
            $this->Location('/admin');
        } else {
            $this->Location('/admin?login_error=1');
        }
    }
}

?>
