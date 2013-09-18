<?php

class pageController extends baseController {

    public function run() {
        $page = new page();
        $page->load(array('url' => registry::getInstance()->getService('request')->url));
        return $page->display();
    }

    public function admin() {
        if (isset($this->params[1])&&$this->params[1]=='blocks'&&isset($this->params[2])) {
            return $this->_pageBlocks();
        } else {
            return parent::admin();
        }
    }

    private function _savePageBlocks($page) {
        $page->blocks = $this->query;
        $page->save();
        header('Location: /admin/page');
    }

    private function _pageBlocks() {
        $page = new page();
        $page->id = $this->params[2];
        $page->load();
        if (count($this->query)>0) $this->_savePageBlocks($page);
        if (count($page->blocks)>0) {
            $item_template = new template('admin/page_blocks_item');
            $data['list'] = '';
            foreach ($page->blocks as $k=>$v) {
                $data['list'] .= $item_template->fill(array_merge($v, array('name'=>$k)));
            }
            $template = new template('admin/page_blocks_list');
            return $template->fill($data);
        }
    }

}

?>
