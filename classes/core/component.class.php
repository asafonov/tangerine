<?php

class component {
    public function __construct() {
    }

    public function init($data=array()) {
        if (count($data)>0) {
            foreach ($data as $k=>$v) {
                $method_name = 'set'.$k;
                if (method_exists($this, $method_name)) {
                    $this->$method_name($v);
                } elseif (property_exists($this, $k)) {
                    $this->$k = $v;
                }
            }
        }
    }

    public function __get($name) {
        $method = 'get'.$name;
        if (method_exists($this, $method)) {
            return $this->$method();
        } elseif(property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new RuntimeException("Error while getting property ".$name);
        }
    }

    public function __set($name, $value) {
        $method = 'set'.$name;
        if (method_exists($this, $method)) {
            $this->$method($value);
        } else {
            throw new RuntimeException("Error while setting property ".$name);
        }
    }
}

?>
