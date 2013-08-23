<?php

class page extends activeRecord {
    
    private $title;
    private $keywords;
    private $description;
    private $url;
    private $name;
    private $layout;
    private $blocks = array();

    public function display() {
        if (count($this->blocks)==0) {
            return false;
        }
        $blocks = array();
        foreach ($this->blocks as $k=>$v) {
            $classname = $v['type'].'Controller';
            unset($spam);
            $spam = new $classname();
            $blocks[$k] = $spam->run($v['data']);
        }
        $template = new template($this->layout);
        return $template->fill($blocks);
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
