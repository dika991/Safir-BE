<?php

namespace App\Traits;

use App\Models\Pemesanan;

trait RandomCode{
    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getCodeForPemesanan(){
        $string = $this->generateRandomString();
        $clear = Pemesanan::where('code', $string)->first();

        if($clear){
            $this->getCodeForPemesanan();
        }
        return $string;
    }
}