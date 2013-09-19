<?php

class page extends activeRecord {
    
    public $title;
    public $keywords;
    public $description;
    public $url;
    public $name;
    public $layout;
    public $blocks = array();

    public function create($data = array()) {
        if (count($data)==0) {
            $data = registry::getInstance()->getService('request')->query;
        }
        $this->init($data);
        $this->save();
    }

    public function display() {
        if (count($this->blocks)==0) {
            return false;
        }
        $blocks = array();
        foreach ($this->blocks as $k=>$v) {
            if ($v['type']!='') {
                if (strpos($v['type'], '->')!==false) {
                    $tmp = explode('->', $v['type']);
                    $classname = $tmp[0].'Controller';
                    $method = $tmp[1];
                } else {
                    $classname = $v['type'].'Controller';
                    $method = 'run';
                }
                unset($spam);
                $spam = new $classname();
                $blocks[$k] = $spam->$method($v['data']);
            } else {
                $blocks[$k] = '';
            }
        }
        $template = new template($this->layout);
        $page_data = array('page_title'=>$this->title, 'page_keywords'=>$this->keywords, 'page_description'=>$this->description);
        return $template->fill(array_merge($blocks, $page_data));
    }

    public function setLayout($value) {
        $this->layout = $value;
    }

    public function getBlocks() {
        return $this->blocks;
    }

    public function setBlocks($value) {
        $this->blocks = $value;
    }
}   

?>