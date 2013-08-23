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
            if (strpos($v['type'], '->')!==false) {
                $spam = explode('->', $v['type']);
                $classname = $tmp[0].'Controller';
                $method = $tmp[1];
            } else {
                $classname = $v['type'].'Controller';
                $method = 'run';
            }
            unset($spam);
            $spam = new $classname();
            $blocks[$k] = $spam->$method($v['data']);
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
