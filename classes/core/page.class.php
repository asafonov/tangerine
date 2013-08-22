<?php

class page extends activeRecord {
    
    private $title;
    private $keywords;
    private $description;
    private $url;
    private $name;
    private $layout;

    public function setUrl($url) {
        if ($url!=$this->url) {
            $this->url = $url;
            $this->load(array('url'=>$url));
        }
    }
}   

?>
