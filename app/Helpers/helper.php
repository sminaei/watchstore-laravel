<?php
namespace App\Helpers;

class Helper{
    public static function make_slug($string){

        return preg_replace('/\s+/u','-',trim($string));
    }
    public static function generateRandomString($length = 20){

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for($i=0; $i<$length; $i++){
            $randomString .= $characters[rand(0, $charactersLength -1 )];
        }
        return $randomString;
    }
}
