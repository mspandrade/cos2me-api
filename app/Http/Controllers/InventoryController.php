<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryItem;
use App\Mapper\InventoryMapper;
use App\Service\InventoryService;
use App\Http\Requests\StoreInventoryItemsList;
use Illuminate\Support\Facades\DB;
use App\Service\CurrentUserHandle;

class InventoryController {
    
    private $inventoryService;
    
    public function __construct(InventoryService $inventoryService) {
        $this->inventoryService = $inventoryService;
    }
    
    public function storeItem(StoreInventoryItem $request) {
        return $this->inventoryService->insertItem(InventoryMapper::mapToInsertItem($request));
    }
    
    public function storeListToInventory(StoreInventoryItemsList $request) {
        
        return DB::transaction(function() use ($request) {
            return $this->inventoryService->storeCopyListToInventory(
                InventoryMapper::mapToIntertItemsOfList($request)
                );
        });
    }
    
    public function showInventory() {
        return $this->inventoryService->showInventory(CurrentUserHandle::getUser());
    }
    
    public function showInventoryList(int $id) {
        return $this->inventoryService->showInventoryList($id, CurrentUserHandle::getUser());
    }
    
    public function showInventoryItem(int $id) {
        return $this->inventoryService->showInventoryItem($id, CurrentUserHandle::getUser());
    }
    
    public function completeStep(int $id) {
        return $this->inventoryService->changeCompleteStatusOfStep($id, true, CurrentUserHandle::getUser());
    }
    
    public function uncompleteStep(int $id) {
        return $this->inventoryService->changeCompleteStatusOfStep($id, false, CurrentUserHandle::getUser());
    }
    
    public function completeItem(int $id) {
        return $this->inventoryService->changeCompleteStatusOfItem($id, true, CurrentUserHandle::getUser());
    }
    
    public function uncompleteItem(int $id) {
        return $this->inventoryService->changeCompleteStatusOfItem($id, false, CurrentUserHandle::getUser());
    }
    
}