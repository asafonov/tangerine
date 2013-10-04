<?php

class record extends activeRecord {

    public $title;
    public $body;
    public $user;
    public $date;
    public $blog;
    public $active = 0;

    public function create($data = array()) {
        if (!registry::getInstance()->getService('user')->id) {
            throw new Exception("Not Authorized", 1);
        }
        if (count($data)==0) {
            $data = registry::getInstance()->getService('request')->query;
        }
        $data['active'] = isset($data['active'])?$data['active']:0;
        $this->init($data);
        if (!$this->body) throw new Exception("Empty record body is not allowed", 1);
        $this->date = intval($this->date)>0?$this->date:time();
        if (!$this->blog) {
            $blog = new blog();
            $blog->load(array('user'=>registry::getInstance()->getService('user')->id));
            if (!$blog->id) {
                $blog->create();
            }
            $this->blog = $blog->id;
        }
        $this->user = registry::getInstance()->getService('user')->id;
        $this->save();
    }

}

?>