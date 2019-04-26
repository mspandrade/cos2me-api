<?php
namespace App\Mapper;

use App\DTOS\DeleteItemDTO;
use App\DTOS\StoreItemDTO;
use App\DTOS\UpdateItemDTO;
use App\Http\Requests\StoreItem;
use App\Http\Requests\UpdateItem;
use App\Service\CurrentUserHandle;

class ItemMapper {
    
    public static function toStore(StoreItem $request): StoreItemDTO {
        
        return new StoreItemDTO(
            
            self::getUser(),
            $request->hashtags,
            $request->description,
            $request->steps,
            $request->materials,
            $request->tutorial,
            $request->video_tutorial,
            $request->minimum_price,
            $request->maximum_price
            );
    }
    
    public static function toDelete(int $id) {
        return new DeleteItemDTO(
            $id,
            CurrentUserHandle::getUser()
            );
    }
    
    public static function toUpdate(int $id, UpdateItem $request) {
        return new UpdateItemDTO(
            $id, 
            CurrentUserHandle::getUser(),
            $request->description,
            $request->steps,
            $request->hashtags,
            $request->materials,
            $request->tutorial,
            $request->video_tutorial,
            $request->minimum_price,
            $request->maximum_price
            );
    }
    
    private static function getUser() {
        return CurrentUserHandle::getUser();
    }
    
}