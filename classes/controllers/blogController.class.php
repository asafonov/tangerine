<?php

class blogController extends baseController {

    private $per_page;

    public function run($blog_id) {
        if (isset($this->params[0])) {
            if ($this->params[0]=='tags')
                return $this->_tags($blog_id);
            else
                return $this->_item($blog_id);
        }
        return $this->_list($blog_id);
    }

    protected function _adminList() {
        $list = new activeList('blog');
        $spam = $list->setQuery(array('user'=>registry::getInstance()->getService('user')->id))->asArray();
        $item_template = new template('admin/blog_item');
        $data = array();
        for ($i=0, $j=count($spam); $i<$j; $i++) {
            $data['list'] .= $item_template->fill($spam[$i]);
        }
        $template = new template('admin/blog_list');
        return $template->fill($data);
    }

    private function _item($blog_id) {
        $this->isAuthorized();
        $record = new record();
        $record->id = $this->params[0];
        $record->load();
        if ($record->blog!=$blog_id) {
            throw new Exception("Record was not found in this blog");
        }
        if ((!isset($this->query['debug'])||!registry::getInstance()->getService('user')->id == $record->user)&&!$record->active) {
            throw new Exception("Record is not active", 1);
        }
        $spam = $record->asArray();
        $spam['date'] = date('Y-m-d', $spam['date']);
        if (count($record->tags)>0) {
            $tags = array();
            $tags_template = new template('record_tags');
            for ($i=0, $j=count($record->tags); $i<$j; $i++) {
                $tags[] = $tags_template->fill(array('tag'=>$record->tags[$i]));
            }
            $spam['tags_text'] = implode(', ', $tags);
        }
        $template = new template('record_full');
        registry::getInstance()->getService('page')->title .= ' | '.$record->title;
        return $template->fill($spam);
    }

    private function _tags($blog_id) {
        $this->per_page = config::getValue('blog_per_page');
        if (!$this->per_page) $this->per_page = 20;
        $list = new activeList('record');
        $skip = isset($this->query['skip'])?intval($this->query['skip']):0;
        $records = $list->setQuery(array('blog'=>$blog_id, 'active'=>1, 'exists'=>array('tags'=>$this->params[1])))->setOrder(array('date'=>-1))->setLimit($this->per_page)->setSkip($skip)->asArray();
        return $this->_displayRecords($records, $blog_id);                
    }

    private function _list($blog_id) {
        $this->per_page = config::getValue('blog_per_page');
        if (!$this->per_page) $this->per_page = 20;
        $list = new activeList('record');
        $skip = isset($this->query['skip'])?intval($this->query['skip']):0;
        $records = $list->setQuery(array('blog'=>$blog_id, 'active'=>1))->setOrder(array('date'=>-1))->setLimit($this->per_page)->setSkip($skip)->asArray();
        return $this->_displayRecords($records, $blog_id);
    }

    private function _displayRecords($records, $blog_id) {
        $template = new template('blog');
        $blog = new blog();
        $blog->id = $blog_id;
        $blog->load();
        $data = $blog->asArray();
        $data['list'] = '';
        $item_template = new template('record');
        $tags_template = new template('record_tags');
        for ($i=0, $j=count($records); $i<$j; $i++) {
            $records[$i]['date'] = date('Y-m-d', $records[$i]['date']);
            if ($records[$i]['tags']!='') {
                $tags = array();
                $records[$i]['tags'] = unserialize($records[$i]['tags']);
                for ($i1=0, $j1=count($records[$i]['tags']); $i1<$j1; $i1++) {
                    $tags[] = $tags_template->fill(array('tag'=>$records[$i]['tags'][$i1]));
                }
                $records[$i]['tags_text'] = implode(', ', $tags);
            }
            $data['list'] .= $item_template->fill($records[$i]);
        }
        return $template->fill($data);
    }

}

?>
