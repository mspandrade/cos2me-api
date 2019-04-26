<?php
namespace App\Mapper;

use App\Http\Requests\StoreRating;
use App\DTOS\StoreRatingDTO;
use App\Service\CurrentUserHandle;
use App\Http\Requests\UpdateRating;
use App\DTOS\UpdateRatingDTO;

class RatingMapper {
    
    public static function mapToInsert(StoreRating $request) {
        return new StoreRatingDTO(
            $request->route('itemId'), 
            $request->value, 
            $request->description, 
            CurrentUserHandle::getUser()
            );
    }
    
    public static function mapToUpdate(UpdateRating $request) {
        return new UpdateRatingDTO(
            $request->route('id'), 
            $request->value, 
            $request->description
            );
    }
    
}