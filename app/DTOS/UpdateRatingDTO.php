<?php
namespace App\DTOS;

class UpdateRatingDTO
{
    private $id;
    private $value;
    private $description;
    
    public function __construct(
        int $id,
        string $value,
        $description
        ) {
        $this->id = $id;
        $this->value = $value;
        $this->description = $description;
    }
    
    public function getId(){
        return $this->id;
    }

    public function getValue(){
        return $this->value;
    }

    public function getDescription(){
        return $this->description;
    }
    
    public function getFillData() {
        return [
            'value'         => $this->value,
            'description'   => $this->description
        ];
    }

}

