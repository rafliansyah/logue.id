<?php

namespace App\Http\Controllers\Api\Utility;

class Utilities
{
    //
    public function substring($string, $from, $to){
      return substr($string, $from, $to - $from);
    }

    public function randomNumber($numLen){
      $chars = "123456789";
      $randomChar = '';
        for ($i = 0; $i < $numLen; $i++){
        $rNum = floor(lcg_value() * $numLen); //floor buat pembulatan, supaya tidak ada comma
        $randomChar .= $this->substring($chars, $rNum, ($rNum+1)); //penggabungan string
        }
        return $randomChar;
    }
}
