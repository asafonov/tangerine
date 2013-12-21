<?php

class blog extends activeRecord {

    public $title;
    public $description;
    public $user;

    public function create($data = array()) {
        if (!registry::getInstance()->getService('user')->id) {
            throw new Exception("Not Authorized", 1);
        }
        if (count($data)==0) {
            $data = registry::getInstance()->getService('request')->query;
        }
        $this->init($data);
        $this->user = isset($data['user']) ? $data['user'] : registry::getInstance()->getService('user')->id;
        $this->save();
    }

}

?>