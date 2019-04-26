<?php

namespace App\DTOS;

use App\User;

class StoreItemDTO {
    
    private $user;
    private $hashTags = [];
    private $description;
    private $steps;
    
    private $materials = [];
    private $videoTutorial;
    private $tutorial;
    private $minimumPrice;
    private $maximumPrice;
    
    public function __construct(
        User $user, 
        array $hashTags,
        string $description,
        array $steps,
        $materials,
        $tutorial,
        $videoTutorial,
        $minimumPrice,
        $maximumPrice
        ) {
        $this->description = $description;
        $this->hashTags = $hashTags;
        $this->user = $user;
        $this->steps = $steps;
        $this->materials = $materials;
        $this->tutorial = $tutorial;
        $this->videoTutorial = $videoTutorial;
        $this->minimumPrice = $minimumPrice;
        $this->maximumPrice = $maximumPrice;
    }
    
    public function getHashTags(): array {
        return $this->hashTags;
    } 
    
    public function getDescription(): string {
        return $this->description;
    }

    public function getUser(): User {
        return $this->user;
    }

    public function getSteps(): array {
        return $this->steps;
    }

    public function getMaterials(){
        return $this->materials;
    }

    public function getVideoTutorial(){
        return $this->videoTutorial;
    }

    public function getTutorial(){
        return $this->tutorial;
    }

    public function getMinimumPrice(){
        return $this->minimumPrice;
    }

    public function getMaximumPrice(){
        return $this->maximumPrice;
    }
    
    public function getFillData(): array {
        
        return [
            'description'       => $this->description,
            'user_id'           => $this->user->id,
            'video_tutorial'    => $this->videoTutorial,
            'tutorial'          => $this->tutorial,
            'minimum_price'     => $this->minimumPrice,
            'maximum_price'     => $this->maximumPrice
        ];
    }

}