<?php
namespace App\DTOS;

use App\User;

class PublishListDTO
{
    private $id;
    private $user;
    
    public function __construct(
        int $id,
        User $user
        ) {
        $this->id = $id;
        $this->user = $user;
    }
    
    public function getId(): int {
        return $this->id;
    }

    public function getUser(): User {
        return $this->user;
    }

}

