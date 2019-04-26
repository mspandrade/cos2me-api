<?php
namespace App\DTOS;

use App\User;

class UpdateItemDTO {
    
    private $id;
    private $user;
    
    private $description;
    private $steps;
    private $hashtags;
    
    private $materials = [];
    private $videoTutorial;
    private $tutorial;
    private $minimumPrice;
    private $maximumPrice;
    
    public function __construct(
        int $id, 
        User $user, 
        $description, 
        $steps, 
        $hashtags,
        $materials,
        $tutorial,
        $videoTutorial,
        $minimumPrice,
        $maximumPrice
        ) {
        $this->id = $id;
        $this->user = $user;
        $this->description = $description;
        $this->steps = $steps;
        $this->hashtags = $hashtags;
        $this->materials = $materials;
        $this->tutorial = $tutorial;
        $this->videoTutorial = $videoTutorial;
        $this->minimumPrice = $minimumPrice;
        $this->maximumPrice = $maximumPrice;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUser(): User {
        return $this->user;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getSteps(){
        return $this->steps;
    }

    public function getHashtags(){
        return $this->hashtags;
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