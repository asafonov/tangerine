<?php

class block extends activeRecord {
    public $name;
    public $value;

    public function create($data = array()) {
        if (count($data)==0) {
            $data = registry::getInstance()->getService('request')->query;
        }
        $this->init($data);
        $this->save();
    }
}

?>