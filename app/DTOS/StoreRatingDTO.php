<?php
namespace App\DTOS;

use App\User;

class StoreRatingDTO
{
    private $itemId;
    private $value;
    private $description;
    private $user;
    
    public function __construct(
        int $itemId,
        string $value,
        $description,
        User $user
        ) {
        $this->itemId = $itemId;
        $this->value = $value;
        $this->description = $description;
        $this->user = $user;
    }

    public function getItemId(){
        return $this->itemId;
    }

    public function getValue(){
        return $this->value;
    }

    public function getDescription(){
        return $this->description;
    }
    
    public function getUser(){
        return $this->user;
    }
    
    public function getFillData() {
        return [
            'item_id'       => $this->itemId,
            'value'         => $this->value,
            'description'   => $this->description,
            'user_id'       => $this->user->id
        ];
    }

}

