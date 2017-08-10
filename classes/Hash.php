<?php


class Hash {

    public static function make($string, $salt){
        return hash('sha256', $string.$salt);
    }

    public static function salt($length){
        $crypto_strong=true;
        return openssl_random_pseudo_bytes ( $length ,$crypto_strong );

    }

    public static function  unique(){
        return Self::make(uniqid());
    }

}
