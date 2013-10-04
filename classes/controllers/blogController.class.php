<?php

class blogController extends baseController {

    private $per_page;

    public function run($blog_id) {
        if (isset($this->params[0])) {
            return $this->_item($blog_id);
        }
        return $this->_list($blog_id);
    }

    protected function _adminList() {
        $list = new activeList('blog');
        $spam = $list->setQuery(array('user'=>registry::getInstance()->getService('user')->id))->asArray();
        $item_template = new template('admin/blog_item');
        for ($i=0, $j=count($spam); $i<$j; $i++) {
            $data['list'] .= $item_template->fill($spam[$i]);
        }
        $template = new template('admin/blog_list');
        return $template->fill($data);
    }

    private function _item($blog_id) {
        $record = new record();
        $record->id = $this->params[0];
        $record->load();
        if ($record->blog!=$blog_id) {
            throw new Exception("Record was not found in this blog");
        }
        if (!$record->active) {
            throw new Exception("Record is not active", 1);
        }
        $spam = $record->asArray();
        $spam['date'] = date('Y-m-d', $spam['date']);
        $template = new template('record_full');
        return $template->fill($spam);
    }

    private function _list($blog_id) {
        $this->per_page = config::getValue('blog_per_page');
        if (!$this->per_page) $this->per_page = 20;
        $list = new activeList('record');
        $records = $list->setQuery(array('blog'=>$blog_id, 'active'=>1))->setOrder(array('date'=>-1))->setLimit($this->per_page)->asArray();
        $template = new template('blog');
        $blog = new blog();
        $blog->id = $blog_id;
        $blog->load();
        $data = $blog->asArray();
        $data['list'] = '';
        $item_template = new template('record');
        for ($i=0, $j=count($records); $i<$j; $i++) {
            $records[$i]['date'] = date('Y-m-d', $records[$i]['date']);
            $data['list'] .= $item_template->fill($records[$i]);
        }
        return $template->fill($data);
    }

}

?>