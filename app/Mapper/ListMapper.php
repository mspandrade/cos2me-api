<?php
namespace App\Mapper;

use App\DTOS\CharacterStoreListDTO;
use App\DTOS\StoreListDTO;
use App\DTOS\UpdateImagesListDTO;
use App\DTOS\UpdateListDTO;
use App\Http\Requests\StoreList;
use App\Http\Requests\UpdateImagesList;
use App\Http\Requests\UpdateList;
use App\Service\CurrentUserHandle;
use App\Http\Requests\PublishList;
use App\DTOS\PublishListDTO;

class ListMapper {
    
    public static function mapToStore(StoreList $request): StoreListDTO {
        
        $character = new CharacterStoreListDTO(
            $request->character['id'] ?? null,
            $request->character['name'] ?? null
            );
        
        return new StoreListDTO(
            $character,
            $request->items,
            $request->hashtags ?: [],
            CurrentUserHandle::getUser(),
            $request->minimum_age ?: 12
            );
    }
    
    public static function mapToUpdate(UpdateList $request): UpdateListDTO {
        
        $character = null;
        
        if (isset($request->character['id']) or isset($request->character['name'])) {
            
            $character = new CharacterStoreListDTO(
                $request->character['id'] ?? null,
                $request->character['name'] ?? null
                );
        }
        
        return new UpdateListDTO(
            (int) $request->route('id'),
            $character,
            $request->items,
            $request->hashtags,
            CurrentUserHandle::getUser(),
            $request->minimum_age
            );
    }
    
    public static function mapToUpdateImage(UpdateImagesList $request): UpdateImagesListDTO {
        
        $referencesRequest = $request->get('reference', []);
        
        $references = array_map(function($value) {
            
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            
        }, $referencesRequest);
            
            return new UpdateImagesListDTO(
                $request->route('id'),
                CurrentUserHandle::getUser(),
                $request->file('file', []),
                $request->get('remove', []),
                $request->get('order', []),
                $references
                );
    }
    
    public static function mapToPublish(PublishList $request) {
        return new PublishListDTO(
            $request->route('id'), 
            CurrentUserHandle::getUser()
            );
    }
    
    
}