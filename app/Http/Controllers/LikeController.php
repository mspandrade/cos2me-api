<?php
namespace App\Http\Controllers;

use App\Mapper\LikeMapper;
use App\Service\CurrentUserHandle;
use App\Service\LikeService;
use Illuminate\Http\Request;

class LikeController {
    
    private $likeService;
    
    public function __construct(LikeService $likeService) {
        $this->likeService = $likeService;
    }
    
    public function store(Request $request) {
        return $this->likeService->insert(LikeMapper::mapToInsert($request));
    }
    
    public function delete(int $listId) {
        $this->likeService->delete($listId, CurrentUserHandle::getUser());
        return [ 'message' => 'Like was deleted'];
    }
    
}