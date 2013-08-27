<?php

class crypt {
    private $secret="Long Live Rock'n'Roll!";
    private $allowed_symbols = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    public function hash() {
        $string ='';
        for($i=0, $j=func_num_args(); $i<$j; $i++){
            $string .= (string)func_get_arg($i);
        }
        return md5($string.$this->secret);
    }

    public function random($length=8) {
        $len = strlen($this->allowed_symbols);
        $ret = '';
        for ($i=0; $i<$length; $i++) {
            $ret .= $this->allowed_symbols[mt_rand(0, $len-1)];
        }
        return $ret;
    } 
}

?>
