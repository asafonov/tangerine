<?php

/**
 * The main controller for working with pages
 *
 * @author Alexander Safonov <me@asafonov.org>
 */
class pageController extends baseController {

    /**
     * Plugin rights
     * @var array
     */
    public $plugin_rights = array(1);

    /**
     * 404 page
     */
    private function _404() {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
        header("Status: 404 Not Found");
        $template = new template('404');
        return $template->fill();
    }

    /**
     * Get the list of blocks of the page
     */
    private function _pageBlocks() {
        $page = new page();
        $page->id = $this->params[2];
        $page->load();
        if (count($this->query)>0) $this->_savePageBlocks($page);
        $data['list'] = '';
        if (count($page->blocks)>0) {
            $item_template = new template('admin/page_blocks_item');
            $list = new activeList('block');
            $blocks = $list->setOrder(array('name'=>1))->asArray();
            $option_template = new template('widgets/option');
            foreach ($page->blocks as $k=>$v) {
                $type='';
                for ($i=0, $j=count($blocks); $i<$j; $i++) {
                    $type .= $option_template->fill(array(
                        'id'=>$blocks[$i]['value'], 
                        'name'=>$blocks[$i]['name'],
                        'selected'=>$blocks[$i]['value']==$v['type']
                    ));
                }
                $data['list'] .= $item_template->fill(array('id'=>md5($k), 'page_id'=>$page->id, 'name'=>$k, 'data'=>$v['data'], 'type'=>$type));
            }
        }
        $template = new template('admin/page_blocks_list');
        return $template->fill($data);
    }

    /**
     * Save the list of blocks of the page
     */
    private function _savePageBlocks($page) {
        if (isset($this->query['delete'])) {
            unset($page->blocks[$this->query['delete']]);
            $page->save();
            header('Location: /admin/page/blocks/'.$this->params[2]);
        } elseif (isset($this->query['block_name'])) {
            $page->blocks[$this->query['block_name']] = array('type'=>'', 'data'=>'');
        } else {
            $page->blocks = $this->query;
            $page->save();
            header('Location: /admin/page');
        }
    }

    /**
     * Administrative interface
     */
    public function admin() {
        if (!$this->checkPluginRights()) return false;
        if (isset($this->params[1])&&$this->params[1]=='blocks'&&isset($this->params[2])) {
            return $this->_pageBlocks();
        } else {
            return parent::admin();
        }
    }

    /**
     * Entry point of the controller
     */
    public function run() {
        $page = registry::getInstance()->getService('page');
        $page_id = registry::getInstance()->getService('request')->currentPage;
        if ($page_id) {
            $page->id = $page_id;
            $page->load();
            return $page->display();
        } else {
            return $this->_404();
        }
    }

}

?>
