<?php


namespace App\Classes;


use Illuminate\Support\Facades\Crypt;

class Enigma
{
    public static function encrypt ($string) {
//        return base64_encode(Crypt::encryptString($string));
        return env('ENCRIPTA', false) ? Crypt::encryptString($string) : $string;
    }

    public static function decrypt ($string) {
//        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(Enigma::$Key),
//            base64_decode($string), MCRYPT_MODE_CBC, md5(md5(Enigma::$Key))), "\0");
//        return Crypt::decryptString(base64_decode($string));
        return env('ENCRIPTA', false) ? Crypt::decryptString($string) : $string;
    }
}
