<?php

/**
 * The administrative interface controller
 *
 * @author Alexander Safonov <me@asafonov.org>
 */
class adminController extends baseController {

    /**
     * Build menu for the current user's role
     * @param int
     * @return string
     */
    private function _buildMenu($role) {
        $template = new template('admin/menu'.intval($role));
        return $template->fill();
    }

    /**
     * Default page
     * @return string
     */
    private function _hello() {
        $template = new template('admin/_hello');
        return $template->fill(array('avatar'=>nl2br(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/images/admin/avatar120.txt'))));
    }

    /**
     * Authorization process
     */
    private function _processLogin() {
        if (registry::getInstance()->getService('user')->login($this->query['login'], $this->query['password'])) {
            $auth = new auth();
            $auth->setSign();
            $this->Location('/admin');
        } else {
            $this->Location('/admin?login_error=1');
        }
    }

    /**
     * Authorization
     * @return string
     */
    public function login() {
        if (count($this->query)>1) {
            return $this->_processLogin();
        }
        $template = new template('admin/login');
        return $template->fill($this->query);
    }

    /**
     * Menu builder
     * @return string
     */
    public function menu() {
        if (!$this->isAuthorized()) {
            return false;
        }
        return $this->_buildMenu(registry::getInstance()->getService('user')->role);
    }

    /**
     * Entry point of the controller
     */
    public function run() {
        if (!$this->isAuthorized()) {
            return $this->login();
        } elseif (isset($this->params[0])) {
            $classname = $this->params[0].'Controller';
            if (!tangerineClassExists($classname)) throw new Exception("Plugin not found: {$this->params[0]}");
            if (!method_exists($classname, 'admin')) throw new Exception("Error in plugin: {$this->params[0]}");
            $spam = new $classname();
            return $spam->admin();
        } elseif(isset($this->query['logout'])) {
            $auth = new auth();
            $auth->deleteSign();
            $this->Location('/admin');
        } else {
            return $this->_hello();
        } 
    }

}

?>
