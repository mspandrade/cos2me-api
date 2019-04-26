<?php
namespace App\Service;

use App\Step;
use App\DTOS\StoreStepDTO;

class StepService
{
    
    public function insert(StoreStepDTO $dto) : Step {
        
        return Step::create([
                        'item_id'       => $dto->getItem()->id,
                        'description'   => $dto->getDescription(),
                        'order'         => $dto->getOrder()
                        ]);
    }
    
}

