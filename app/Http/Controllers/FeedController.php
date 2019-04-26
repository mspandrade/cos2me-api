<?php
namespace App\Http\Controllers; 

use App\Http\Requests\SearchFeed;
use App\Mapper\FeedMapper;
use App\Service\CurrentUserHandle;
use App\Service\FeedService;


class FeedController {
    
    private $feedService;
    
    public function __construct(FeedService $feedService) {
        $this->feedService = $feedService;
    }
    
    public function feed(string $username) {
        
        return $this->feedService->byUser($username);
    }
    
    public function me() {
        return $this->feedService->me(CurrentUserHandle::getUser());
    }
    
    public function searchByHashtags(SearchFeed $request) {
        return $this->feedService->searchByHashtags(
            FeedMapper::mapToSearch($request)
            );
    }
    
    public function searchByCharacter(SearchFeed $request) {
        return $this->feedService->searchByCharacter(
            FeedMapper::mapToSearch($request)
            );
    }
    
}