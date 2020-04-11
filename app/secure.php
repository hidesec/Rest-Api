<?php

namespace App;
use Illuminate\Support\Str;

class secure
{
   
   public function encode($data)
   {
    $random = Str::random(1090);
    $base_64 = $random.'SQ?='.base64_encode(base64_encode(base64_encode(base64_encode(base64_encode($data)))));
    return $base_64;
   }

   public function decode($data)
   {
    $decryptions = substr($data,1094);
    $decryption = base64_decode(base64_decode(base64_decode(base64_decode(base64_decode($decryptions)))));
    return $decryption;     
   }
}