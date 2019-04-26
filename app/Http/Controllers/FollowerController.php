<?php
namespace App\Http\Controllers;

use App\Service\FollowerService;

class FollowerController {
    
    private $followService;
    
    public function __construct(FollowerService $followService) {
        $this->followService = $followService;
    }
    
    public function follow(string $username) {
        $this->followService->follow($username);
        return [ 'message' => 'Followed' ];
    }
    
    public function unfollow(string $username) {       
        $this->followService->follow($username);
        return [ 'message' => 'Unfollowed' ];
    }
    
}