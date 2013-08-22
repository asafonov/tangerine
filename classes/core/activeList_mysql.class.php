<?php
class activeList extends component {
    private $fields=array();
    private $query=array();
    private $limit = 0;
    private $skip = 0;
    private $_connector;
    private $_table;

    public function __construct($table = false) {
        $this->_connector = new mysqli($this->_host, $this->_login, $this->_password, $this->_database);
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
    }

    public function setFields($fields=array()) {
        if (is_array($fields)&&count($fields)>0) {
            foreach ($fields as $k=>$v) {
                $this->$fields[$k] = $v;
            }
        } else {
            throw new RuntimeException("Incorrect data format");
        }
    }

    public function setLimit($limit) {
        $this->limit = $limit;
    }

    public function setSkip($skip) {
        $this->skip = $skip;
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
