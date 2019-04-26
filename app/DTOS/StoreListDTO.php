<?php
namespace App\DTOS;

use App\User;

class StoreListDTO {
    
    private $items;
    private $hashtags;
    private $character;
    private $user;
    private $minimumAge;
    
    public function __construct(
        CharacterStoreListDTO $character, 
        array $items, 
        array $hashtags, 
        User $user, 
        int $minimumAge
        ) {
        $this->character = $character;
        $this->items = $items;
        $this->hashtags = $hashtags;
        $this->user = $user;
        $this->minimumAge = $minimumAge;
    }

    public function getItems(): array {
        return $this->items;
    }

    public function getHashtags(): array {
        return $this->hashtags;
    }

    public function getCharacter(): CharacterStoreListDTO {
        return $this->character;
    }

    public function getUser(): User {
        return $this->user;
    }

    public function getMinimumAge(): int {
        return $this->minimumAge;
    }

}