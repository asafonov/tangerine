<?php

class baseController extends component {

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

    public function admin() {
        if (isset($this->query['id'])) return $this->_adminItem();
        if (isset($this->query['delete'])) return $this->_deleteAdminItem();
        return $this->_adminList();
    }

    protected function _adminList() {
        $plugin_name = $this->params[0];
        $list = new activeList($plugin_name);
        $spam = $list->asArray();
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
        $spam->id = intval($this->query['id']);
        $spam->load();
        if (count($this->query)>1) $this->_saveAdminItem($spam);
        $template = new template('admin/'.$plugin_name.'_edit');
        $data = $spam->asArray();
        return $template->fill($data);
    }
}

?>
