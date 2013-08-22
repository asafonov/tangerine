<?php

class activeRecord extends component {

    private $id;
    private $_connector;
    private $_host;
    private $_login;
    private $_password;
    private $_database;
    private $_table;

    public function __construct() {
        $this->_connector = new mysqli($this->_host, $this->_login, $this->_password, $this->_database);
        $this->_table = get_class($this);
    }

    public function setId($value) {
        $this->id = $value;
        $this->load();
    }

    public function load($criteria = null) {
        if ($criteria = null) {
            if ($this->id) {
                $criteria = array('id'=>$this->id);
            } else {
                throw new RuntimeException("There is no criteria for selecting an object");
            }
        }
        $sql = 'select * from '.$this->_table.' where 1=1';
        foreach ($criteria as $k=>$v) {
            $sql .= " and $k = '$v'";
        }
        $result = $this->_connector->query($sql);
        $spam = $result->fetch_assoc();
        $this->init($spam);
    }

    public function save() {
        $spam = get_object_vars($this);
        foreach ($spam as $k=>$v) {
            if (strpos($k, '_')===0) {
                unset($spam[$k]);
            }
        }
        if ($this->id) {
            unset($spam['id']);
            $sql = 'update '.$this->_table.' set';
            $i=0;
            foreach($spam as $k=>$v) {
                $sql .= ($i>0?', ':' ')."$k='$v'";
            }
            $sql .= ' where id = '.$this->id;
        } else {
            $result = $this->_connector->query('select max(id) as maxid from '.$this->_table);
            $tmp = $result->fetch_assoc();
            $this->id = $tmp['id']>0?$tmp['id']+1:1;
            $sql = 'insert into '.$this->_table.' (id';
            $sql_values = 'values ('.$this->id;
            foreach ($spam as $k=>$v) {
                $sql .= ', '.$k;
                $sql_values .= ", '$v'";
            }
            $sql = $sql.') '.$sql_values.')';
        }
        $this->_connector->query($sql);
    }

}

?>
