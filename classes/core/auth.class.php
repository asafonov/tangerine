<?php

class auth {

    private $crypt;

    public function __construct() {
        $this->crypt = new crypt();
    }

    public function checkSign() {
        $cookie = registry::getInstance()->getService('request')->cookie;
        if (!isset($cookie['id'])||!isset($cookie['expire'])||!isset($cookie['sign'])) {
            return false;
        }
        $cookie_sign = $this->crypt->hash($cookie['id'], $cookie['expire']);
        if ($cookie_sign != $cookie['sign']){
            return false;
        }
        $user = registry::getInstance()->getService('user');
        $user->id = $cookie['id'];
        $user->load();
        $time = time();
        if ($time-$user->last_visit>120) {
            $user->last_visit = $time;
            $user->save();
        }
        return true;
    }

    public function setSign() {
        $id = registry::getInstance()->getService('user')->id;
        $expire = time() + (3600*24*30);
        $sign = $this->crypt->hash($id, $expire);
        setcookie('id', $id, $expire, '/');
        setcookie('expire', $expire, $expire, '/');
        setcookie('sign', $sign, $expire, '/');
    }

    public function deleteSign() {
        setcookie('id', '', time()-3600, '/');
        setcookie('expire', '', time()-3600, '/');
        setcookie('sign', '', time()-3600, '/');
    }
}

?>
