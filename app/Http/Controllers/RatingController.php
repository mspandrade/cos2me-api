<?php
namespace App\Http\Controllers;

use App\Service\RatingService;
use App\Http\Requests\StoreRating;
use App\Mapper\RatingMapper;
use App\Http\Requests\UpdateRating;
use App\Http\Requests\DeleteRating;

class RatingController {
    
    private $ratingService;
    
    public function __construct(RatingService $ratingService) {
        $this->ratingService = $ratingService;
    }
    
    public function store(StoreRating $request) {
        return $this->ratingService->insert(RatingMapper::mapToInsert($request));
    }
    
    public function update(UpdateRating $request) {
        return $this->ratingService->update(RatingMapper::mapToUpdate($request));
    }
    
    public function delete(DeleteRating $request) {
        $this->ratingService->delete($request->id);
        return [
            'message' => 'Rating was deleted'
        ];
    }
    
}