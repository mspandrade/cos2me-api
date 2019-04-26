<?php
namespace App\Mapper;

use App\DTOS\StoreInventoryItemDTO;
use App\DTOS\StoreInventoryItemsListDTO;
use App\Http\Requests\StoreInventoryItem;
use App\Http\Requests\StoreInventoryItemsList;
use App\Service\CurrentUserHandle;

class InventoryMapper {
    
    public static function mapToInsertItem(StoreInventoryItem $request) {
        return new StoreInventoryItemDTO(
            CurrentUserHandle::getUser(), 
            $request->route('id')
            );
    }
    
    public static function mapToIntertItemsOfList(StoreInventoryItemsList $request) {
        return new StoreInventoryItemsListDTO(
            CurrentUserHandle::getUser(), 
            $request->route('id')
            );
    }
    
}