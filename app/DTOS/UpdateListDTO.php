<?php
namespace App\DTOS;

use App\User;

class UpdateListDTO {
    
    private $id;
    private $items;
    private $hashtags;
    private $character;
    private $user;
    private $minimumAge;
    
    public function __construct(
        int $id,
        CharacterStoreListDTO $character,
        $items,
        $hashtags,
        User $user,
        $minimumAge
        ) {
        $this->id = $id;    
        $this->character = $character;
        $this->items = $items;
        $this->hashtags = $hashtags;
        $this->user = $user;
        $this->minimumAge = $minimumAge;
    }
    
    public function getItems() {
        return $this->items;
    }
    
    public function getHashtags() {
        return $this->hashtags;
    }
    
    public function getCharacter() {
        return $this->character;
    }
    
    public function getUser(): User {
        return $this->user;
    }
    
    public function getMinimumAge() {
        return $this->minimumAge;
    }
    
    public function getId(): int {
        return $this->id;
    }
    
}