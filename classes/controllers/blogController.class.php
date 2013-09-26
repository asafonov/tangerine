<?php

class blogController extends baseController {

    private $per_page;

    public function run($blog_id) {
        return $this->_list($blog_id);
    }

    private function _list($blog_id) {
        $this->per_page = config::getValue('blog_per_page');
        if (!$this->per_page) $this->per_page = 20;
        $list = activeList('record');
        $records = $list->setQuery(array('blog'=>$blog_id))->setOrder(array('date'=>-1))->setLimit($this->per_page)->asArray();
        $template = new template('blog');
        $blog = new blog();
        $blog->id = $blog_id;
        $blog->load();
        $data = $blog->asArray();
        $data['list'] = '';
        $item_template = new template('record');
        for ($i=0, $j=count($records); $i<$j; $i++) {
            $data['list'] .= $item_template->fill($records[$i]);
        }
        return $template->fill($data)
    }

}

?>