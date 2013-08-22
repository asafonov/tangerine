<?php

class crypt {
    private $secret="Long Live Rock'n'Roll!";

    public function hash() {
        $string ='';
        for($i=0, $j=func_num_args(); $i<$j; $i++){
            $string .= (string)func_get_arg($i);
        }
        return md5($string.$this->secret);
    }
}

?>
