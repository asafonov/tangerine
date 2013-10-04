<?php

class baseController extends component {
    public $plugin_rights = array(1);

    public function isAuthorized() {
        $auth = new auth();
        return $auth->checkSign();
    }

    public function getCookie() {
        return registry::getInstance()->getService('request')->cookie;
    }

    public function getQuery() {
        return registry::getInstance()->getService('request')->query;
    }

    public function getParams() {
        return registry::getInstance()->getService('request')->params;
    }

    public function getFiles() {
        return registry::getInstance()->getService('request')->files;
    }

    public function Location($location) {
        header('Location: '.$location);
        die();
    }

    public function checkPluginRights() {
        return in_array(registry::getInstance()->getService('user')->role, $this->plugin_rights);
    }

    public function admin() {
        if (!$this->checkPluginRights()) return false;
        if (isset($this->query['id'])) return $this->_adminItem();
        if (isset($this->query['delete'])) return $this->_deleteAdminItem();
        return $this->_adminList();
    }

    protected function _adminList() {
        $plugin_name = $this->params[0];
        $list = new activeList($plugin_name);
        $spam = $list->setOrder(array('id'=>1))->asArray();
        $data['list'] = '';
        $item_template = new template('admin/'.$plugin_name.'_item');
        for ($i=0, $j=count($spam); $i<$j; $i++) {
            $data['list'] .= $item_template->fill($spam[$i]);
        }
        $template = new template('admin/'.$plugin_name.'_list');
        return $template->fill($data);
    }

    protected function _adminItem() {
        $plugin_name = $this->params[0];
        $spam = new $plugin_name();
        if ($this->query['id']!='') {
            $spam->id = $this->query['id'];
            $spam->load();
        }
        if (count($this->query)>1) $this->_saveAdminItem($spam);
        $template = new template('admin/'.$plugin_name.'_edit');
        $data = $spam->asArray();
        return $template->fill($data);
    }

    private function _deleteAdminItem() {
        $plugin_name = $this->params[0];
        $spam = new $plugin_name();
        $spam->id = $this->query['delete'];
        $spam->delete();
        $this->Location('/admin/'.$plugin_name);
    }

    private function _saveAdminItem($item) {
        $plugin_name = $this->params[0];
        $item->create();
        $this->Location('/admin/'.$plugin_name);
    }
}

?>
