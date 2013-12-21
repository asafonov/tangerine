<?php

/**
 * Model for processing blog record
 *
 * @author Alexander Safonov <me@asafonov.org>
 */
class record extends activeRecord {

    /**
     * Record title
     * @var string
     */
    public $title;
    /**
     * Record body
     * @var string
     */
    public $body;
    /**
     * User_ID of the author
     * @var string
     */
    public $user;
    /**
     * Date of creation
     * @var int
     */
    public $date;
    /**
     * ID of the blog
     * @var string
     */
    public $blog;
    /**
     * Flag if the record is active
     * @var int
     */
    public $active = 0;
    /**
     * Tags of the record
     * @var array
     */
    public $tags = array();

    /**
     * Create record
     * @param array
     */
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
        $this->user = isset($data['user'])? $data['user']: registry::getInstance()->getService('user')->id;
        $this->save();
    }

}

?>