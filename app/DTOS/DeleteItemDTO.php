<?php
namespace App\DTOS;

use App\User;

class DeleteItemDTO {
    
    private $id;
    private $user;
    
    public function __construct(int $id, User $user) {
        $this->id = $id;
        $this->user = $user;
    }

    public function getId(){
        return $this->id;
    }

    public function getUser(){
        return $this->user;
    }

}