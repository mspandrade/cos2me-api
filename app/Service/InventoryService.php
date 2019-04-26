<?php
namespace App\Service;

use App\InventoryItem;
use App\ListResource;
use App\DTOS\StoreInventoryItemDTO;
use App\DTOS\StoreInventoryItemsListDTO;
use App\Item;
use App\InventoryItemStep;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Mapper\TypeMapper;
use App\Type\ResourceType;
use Illuminate\Support\Collection;

class InventoryService {
    
    public function insertItem(StoreInventoryItemDTO $dto) {
        
        $count =InventoryItem::where('item_id', $dto->getItemId())
                        ->where('user_id', $dto->getUser()->id)
                        ->whereNull(ListResource::LIST_ID)
                        ->count();
        
        if ($count > 0) {
            abort(403, 'You already has this item in your inventory'); 
        }
        
        $inventoryItem = InventoryItem::create($dto->getFillData());
        
        $this->addSteps(
            Item::with('steps')->findOrFail($dto->getItemId()), 
            $inventoryItem
            );
        
        return $inventoryItem;
    }
    
    public function storeCopyListToInventory(StoreInventoryItemsListDTO $dto) {
        
        $list = $this->copyTo($dto->getListId(), $dto->getUser());
        
        return $this->addListToInventory($list, $dto->getUser());
    }
    
    public function addListToInventory(ListResource $list, User $user) {
        
        return $list->items->map(function($item) use($list, $user) {
            
            $inventoryItem = InventoryItem::create([
                'item_id'               => $item->id,
                ListResource::LIST_ID   => $list->id,
                'user_id'               => $user->id
            ]);
            
            $this->addSteps($item, $inventoryItem);
            
            return $inventoryItem;
        });
    }
    
    public function showInventory(User $user) {
        
        $inventory = $this->getIds($user);
        
        $this->transformPageOfItemsLists($inventory, $user);
        
        return $inventory;
    }
    
    public function insertItemList(Collection $itemIds, ListResource $list) {
        
        $itemIds->map(function($itemId) use($list){
           
            $inventoryItem = InventoryItem::create([
                'user_id'   => $list->user_id,
                'list_id'   => $list->id,
                'item_id'   => $itemId
            ]);
            
            $this->addSteps(
                Item::findOrFail($itemId), 
                $inventoryItem
                );
        });
    }
    
    public function removeItemsListByItemId(array $itemIds, ListResource $list) {
                
        InventoryItemStep::whereHas('inventoryItem', function($query) use($itemIds, $list) {
            
            $query->where('list_id', $list->id)
                  ->where('user_id', $list->user_id)
                  ->whereIn('item_id', $itemIds);
            
        })->delete();
        
        InventoryItem::where('list_id', $list->id)
                       ->where('user_id', $list->user_id)
                       ->whereIn('item_id', $itemIds)
                       ->delete();
    }
    
    public function removeItemListByList(ListResource $list) {
        
        InventoryItemStep::whereHas('inventoryItem', function($query) use($list) {
            
            $query->where('list_id', $list->id)
                  ->where('user_id', $list->user_id);
            
        })->delete();
        
        InventoryItem::where('list_id', $list->id)
                    ->where('user_id', $list->user_id)
                    ->delete();
    }
    
    public function showInventoryList(int $id, User $user) {
        
        return ListService::inventoryQuery($user)
            ->with('inventoryItems', 'inventoryItems.item')
            ->whereNull('posted_at')
            ->findOrFail($id);
    }
    
    public function showInventoryItem(int $id, User $user) {
        
        return InventoryItem::with('item', 'item.images', 'item.hashtags', 'steps', 'steps.step')
                        ->withCount('stepsComplete', 'steps')
                        ->where('user_id', $user->id)
                        ->findOrFail($id);
    }
    
    public function changeCompleteStatusOfItem(int $inventoryItemId, $isComplete, User $user) {
        $item = InventoryItem::where('is_complete', !$isComplete)->findOrFail($inventoryItemId);
        
        if ($item->user_id != $user->id) {
            abort(403, 'This inventory item is not yours');
        }
        $item->is_complete = $isComplete;
        $item->save();
        return $item;
    }
    
    public function changeCompleteStatusOfStep(int $inventoryStepId, bool $isComplete, User $user) {
        
        $step = InventoryItemStep::where('is_complete', !$isComplete)->findOrFail($inventoryStepId);
        
        $item = InventoryItem::withCount('steps', 'stepsComplete')->find($step->inventory_item_id);
        
        if ($item->user_id != $user->id) {
            abort(403, 'This inventory step item is not yours');
        }
        
        $step->is_complete = $isComplete;
        $step->save();
        
        if (!$step->is_complete) {
            $item->is_complete = false;
        } else if ($item->steps_complete_count + 1 >= $item->steps_complete_count) {
            $item->is_complete = true;
        }
        
        $item->save();
        
        return $step;
    }
    
    private function copyTo(int $listId, User $user) {
        
        $list = ListResource::with('items', 'images', 'hashtags')->findOrFail($listId);
        
        $newList = $list->replicate();
        $newList->user_id = $user->id;
        $newList->posted_at = null;
        
        $newList->save();
        
        $images = $newList->images->reject(function($image) {
            
            return !$image->is_reference;
            
        })->map(function($image) use($newList) {
            
            $i = $image->replicate();
            $i->list_id = $newList->id;
            
            return $i;
        });
            
        foreach ($images as $image) {
            $image->save();
        }
            
        $newList->items()->attach(
            $list->items->map(function($i) {
                return $i->id;
            })->toArray()
        );
            
        $newList->hashtags()->attach($list->hashtags->map(function($h) {
            return $h->id;
        }));
            
        return $newList;
    }
    
    private function transformPageOfItemsLists(Collection $inventory, User $user) {

        $lists  = ListService::inventoryQuery($user)
                        ->whereIn('id',
                                    TypeMapper::mapId($inventory, ResourceType::LIST)->toArray())
                        ->get()
                        ->getDictionary();
        
        $items = $this->inventoryItemsById(TypeMapper::mapId($inventory, ResourceType::ITEM)->toArray())->getDictionary();
        
        $inventory->transform(function($value, $key) use($items, $lists){
            
            $element = $value->type == 0 ? $items[$value->id] : $lists[$value->id];
            $element->type = $value->type;
            
            return $element;
        });
    }
        
    private function inventoryItemsById(array $ids) {
        return InventoryItem::with('item.images', 'item.hashtags')
                        ->withCount('steps', 'stepsComplete')
                        ->whereIn('id', $ids)
                        ->get();
    }
    
    private function getIds(User $user) {
        
        $itemQuery = InventoryItem::select('id', DB::raw("NULL as posted_at"), 'user_id', 'created_at', DB::raw("0 as type"));
        
        return ListResource::select('id', 'posted_at', 'user_id', 'created_at',  DB::raw("1 as type"))
                                    ->union($itemQuery)
                                    ->whereNull('posted_at')
                                    ->where('user_id', $user->id)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
    }
    
    private function addSteps(Item $item, InventoryItem $inventoryItem) {
        
        $steps = $item->steps->transform(function($step) use($item, $inventoryItem) {
            
            return [
                'step_id'       => $step->id,
                'inventory_item_id' => $inventoryItem->id
            ];
        });
        InventoryItemStep::insert($steps->toArray());
        
        $inventoryItem->load('steps');
    }
    
}