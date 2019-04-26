<?php
namespace App\DTOS;

use App\User;

class StoreInventoryItemDTO {
    
    private $itemId;
    private $user;
    
    public function __construct(User $user, int $itemId) {
        $this->user = $user;
        $this->itemId = $itemId;
    }

    public function getItemId(){
        return $this->itemId;
    }

    public function getUser(){
        return $this->user;
    }

    public function getFillData() {
        return [
            'item_id'   => $this->itemId,
            'user_id'   => $this->user->id
        ];
    }
    
}