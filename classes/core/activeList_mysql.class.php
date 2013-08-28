<?php
class activeList extends component {
    private $fields=array();
    private $query=array();
    private $limit = 0;
    private $skip = 0;
    private $order = array();
    private $_connector;
    private $_host;
    private $_login;
    private $_password;
    private $_database;
    private $_table;

    public function __construct($table = false) {
        $this->_host = !$this->_host?config::getValue('mysql_host'):$this->_host;
        $this->_login = !$this->_login?config::getValue('mysql_login'):$this->_login;
        $this->_password = !$this->_password?config::getValue('mysql_password'):$this->_password;
        $this->_database = !$this->_database?config::getValue('mysql_database'):$this->_database;
        $this->_connector = new mysqli($this->_host, $this->_login, $this->_password, $this->_database);
        $this->_connector->query('set names utf8');
        $this->_table = $table?$table:get_class($this);
    }

    public function setQuery($query=array()) {
        if (is_array($query)&&count($query)>0) {
            foreach ($query as $k=>$v) {
                $this->$query[$k] = $v;
            }
        } else {
            throw new RuntimeException("Incorrect data format");
        }
        return $this;
    }

    public function setFields($fields=array()) {
        if (is_array($fields)&&count($fields)>0) {
            foreach ($fields as $k=>$v) {
                $this->$fields[$k] = $v;
            }
        } else {
            throw new RuntimeException("Incorrect data format");
        }
        return $this;
    }

    public function setLimit($limit) {
        $this->limit = $limit;
        return $this;
    }

    public function setSkip($skip) {
        $this->skip = $skip;
        return $this;
    }

    public function setOrder($order) {
        $this->order = array_merge($this->order, $order);
        return $this;
    }

    private function _createSQL() {
        $sql = 'select '.
        (count($this->fields)>0?implode(', ', $this->fields):'*').' '.
        'from '.$this->_table;
        if (count($this->query)>0) {
            $sql .= ' where 1=1';
            foreach ($this->query as $k=>$v) {
                $sql .= " and $k = '$v'";
            }
        }
        $sql .= count($this->order)>0?' order by '.implode(', ', $this->order):'';
        $sql .= $this->limit>0?' limit '.intval($this->skip).', '.$this->limit:'';
        return $sql;
    }

    

    public function asArray() {
        $sql = $this->_createSQL();
        $result = $this->_connector->query($sql);
        $ret = array();
        while ($spam = $result->fetch_assoc()) {
            $ret[] = $spam;
        }
        return $ret;
    }
}
?>
