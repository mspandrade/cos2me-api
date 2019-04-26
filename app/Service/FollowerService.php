<?php
namespace App\Service;

use App\User;
use App\Follower;

class FollowerService {
    
    public function follow(string $username) {
        
        $user = User::where('username', $username)->firstOrFail();
        
        return Follower::firstOrCreate(
            [
                'user_id'       => $user->id,
                'follower_id'   => $this->getUser()->id
            ]
        );
    }
    
    public function unfollow(int $username) {
        
        $user = User::where('username', $username)->firstOrFail();
        
        return Follower::where(
            [
                'user_id'       => $user->id,
                'follower_id'   => $this->getUser()->id
            ]
         )->delete();
    }
    
    private function getUser(): User {
        return CurrentUserHandle::getUser();
    }
    
}