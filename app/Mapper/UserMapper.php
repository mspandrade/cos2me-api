<?php
namespace App\Mapper;

use Illuminate\Http\Request;
use App\User;

class UserMapper
{
    
    public static function fromRequest(Request $request) : User {
        
        $user = new User();
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->username = $request->username;
        $user->date_birth = $request->date_birth;
        
        return $user;
    }
    
}

