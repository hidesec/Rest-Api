<?php

namespace App;
use Illuminate\Support\Str;

class secure
{
   
   public function encode($data)
   {
    $random = Str::random(70);
    $base_64 = $random.'SQ?='.base64_encode(base64_encode(base64_encode(base64_encode(base64_encode($data)))));
    return $base_64;
   }

   public function decode($data)
   {
    $decryptions = substr($data,74);
    $decr = base64_decode(base64_decode(base64_decode(base64_decode(base64_decode($decryptions)))));
   return  $decr;    
   }
}