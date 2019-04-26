<?php
namespace App\DTOS;

use App\User;

class StoreLikeDTO {
    
    private $user;
    private $listId;
    
    public function __construct(User $user, int $listId) {
        $this->user = $user;
        $this->listId = $listId;
    }

    public function getUser(){
        return $this->user;
    }

    public function getListId(){
        return $this->listId;
    }
    
    public function getFillData() {
        return [
            'user_id'   => $this->user->id,
            'list_id'   => $this->listId
        ];
    }

}