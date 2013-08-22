<?php

class registry {

    private static $instance;
    private $objects;

    public function __construct() {
        ;
    }

    public static function getInstance(){
        if(!isset(self::$instance)) {
            self::$instance=new registry();
        }
        return self::$instance;
    }

    public function getService($name) {
        if (isset($this->objects[$name])) {
            return $this->objects[$name];
        } elseif ($object = $this->_getObject($name)) {
            return $object;
        } else {
            throw new RuntimeException('Can not get appropriate service for '.$name);
        }
    }

    private function _getObject($name) {
        $classname = config::getValue('services')[$name];
        if (class_exists($classname)) {
            $this->objects[$name] = new $classname();
            $init_data = config::getValue($classname);
            if (is_array($init_data)) {
                $this->objects[$name]->init($init_data);
            }
            return $this->objects[$name];
        } else {
            return false;
        }
    } 
}

?>
