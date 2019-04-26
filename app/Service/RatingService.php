<?php
namespace App\Service;

use App\Item;
use App\Rating;
use App\DTOS\StoreRatingDTO;
use App\DTOS\UpdateRatingDTO;

class RatingService {
    
    public function insert(StoreRatingDTO $dto) {
        
        $itemCount = Item::where([
            'id'        => $dto->getItemId(),
            'user_id'   => $dto->getUser()->id
        ])->count();
        
        if ($itemCount > 0) {
            abort(403, 'You cannot rating your item');
        }
        
        $ratingCount = Rating::where([
            'item_id' => $dto->getItemId(),
            'user_id' => $dto->getUser()->id
        ])->count();
        
        if ($ratingCount > 0)  {
            abort(409, 'Your already rating this item');
        }
        
        return Rating::create($dto->getFillData());
    }
    
    public function update(UpdateRatingDTO $dto) {
        $rating = Rating::findOrFail($dto->getId());
        $rating->fill($dto->getFillData())->save();
        return $rating;
    }
    
    public function delete(int $id) {
        Rating::findOrFail($id)->delete();
    }
    
}