<?php

class recordController extends baseController {

    protected function _adminList() {
        if (!isset($this->params[1])) {
            throw new Exception("Not enough parameters", 1);
        }
        $list = new activeList('record');
        $spam = $list->setQuery(array('user'=>registry::getInstance()->getService('user')->id, 'blog'=>$this->params[1]))->setOrder(array('date'=>-1))->asArray();
        $item_template = new template('admin/record_item');
        for ($i=0, $j=count($spam); $i<$j; $i++) {
            $spam[$i]['date'] = date('Y-m-d', $spam[$i]['date']);
            $data['list'] .= $item_template->fill($spam[$i]);
        }
        $template = new template('admin/record_list');
        return $template->fill($data);
    }

    protected function _adminItem() {
        $plugin_name = 'record';
        $spam = new $plugin_name();
        if ($this->query['id']!='') {
            $spam->load(array(
                'id'=>$this->query['id'], 
                'user'=>registry::getInstance()->getService('user')->id, 
                'blog'=>$this->params[1]
            ));
        }
        if (count($this->query)>1) $this->_saveAdminItem($spam);
        $template = new template('admin/'.$plugin_name.'_edit');
        $data = $spam->asArray();
        if (intval($data['date'])>0) $data['date'] = date('Y-m-d', $data['date']);
        return $template->fill($data);
    }

    protected function _saveAdminItem($item) {
        $plugin_name = 'record';
        $data = $this->query;
        if (preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/',$data['date'])) {
            $date = $data['date'];
            $data['date'] = mktime(0,0,0,substr($date,5,2),substr($date,8,2),substr($date,0,4));
        }
        $item->create($data);
        $this->Location('/admin/'.$plugin_name.'/'.$this->params[1].'/');
    }

    protected function _deleteAdminItem() {
        $plugin_name = 'record';
        $spam = new $plugin_name();
        $spam->id = $this->query['delete'];
        $spam->delete();
        $this->Location('/admin/'.$plugin_name.'/'.$this->params[1].'/');
    }

}

?>