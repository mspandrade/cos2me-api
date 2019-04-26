<?php
namespace App\DTOS;

use App\User;

class StoreInventoryItemsListDTO {
    
    private $listId;
    private $user;
    
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

}