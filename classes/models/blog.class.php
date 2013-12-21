<?php

/**
 * Model for processing blog
 *
 * @author Alexander Safonov <me@asafonov.org>
 */
class blog extends activeRecord {

    /**
     * Blog title
     * @var string
     */
    public $title;
    /**
     * Blog description
     * @var string
     */
    public $description;
    /**
     * User_ID of Blog cretor
     * @var string
     */
    public $user;

    /**
     * Create blog
     * @param array
     */
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