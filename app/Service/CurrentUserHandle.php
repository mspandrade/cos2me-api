<?php
namespace App\Service;


class CurrentUserHandle
{
    
    public static function getUser() {
        return auth()->guard('api')->user();
    }
    
}

