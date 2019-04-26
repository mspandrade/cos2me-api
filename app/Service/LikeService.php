<?php
namespace App\Service;

use App\DTOS\StoreLikeDTO;
use App\Like;
use App\User;

class LikeService {
    
    public function insert(StoreLikeDTO $dto) {
        
        $like = $this->find($dto->getListId(), $dto->getUser());
        
        if ($like == null) {   
            $like = Like::create($dto->getFillData());
        }
        return $like;
    }
    
    public function delete(int $listId, User $user) {
        $this->find($listId, $user)->delete();
    }
        
    private function find(int $listId, User $user) {
        return Like::where('list_id',$listId)->where('user_id',$user->id)->first();
    }
    
}