<?php
namespace App\Service;

use Illuminate\Support\Facades\Hash;
use App\User;

class UserService
{
    
    public function insert(User $user) : User {
        
        $user->password = Hash::make($user->password);
        
        $user->save();
        
        return $user;
    }
    
    public function update(User $userUpdated) : User {
        
        $userUpdated->save();
    }
    
}

