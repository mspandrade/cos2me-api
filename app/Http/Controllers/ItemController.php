<?php
namespace App\Http\Controllers;

use App\Item;
use App\Http\Requests\StoreItem;
use App\Http\Requests\UpdateImagesItem;
use App\Http\Requests\UpdateItem;
use App\Mapper\ItemImagesMapper;
use App\Mapper\ItemMapper;
use App\Service\ItemImageService;
use App\Service\ItemService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\DeleteItem;

class ItemController
{
    
    private $itemService;
    private $itemImageService;
    
    public function __construct(ItemService $itemService, ItemImageService $itemImageService) {
        $this->itemService = $itemService;
        $this->itemImageService = $itemImageService;
    }
    
    public function store(StoreItem $request) : Item {
        
        return DB::transaction(function() use($request) {
           
            return $this->itemService->insert(ItemMapper::toStore($request));
        });
    }
    
    public function find(int $id) {
        
        $item = $this->itemService->findById($id);
        
        if ($item == null) {
            throw new NotFoundHttpException();
        }
        
        return $item;
    }
    
    public function updateImagesItem(int $id, UpdateImagesItem $request) {
        
        return DB::transaction(function() use ($id, $request){
            
            return $this->itemService
                        ->updateImages(ItemImagesMapper::toUpdateImages($id, $request));
        });
    }
    
    public function delete(DeleteItem $request) {
        
        return DB::transaction(function() use($request) {
            
            $this->itemService->delete(ItemMapper::toDelete($request->route('id')));
            
            return [ 'message' => 'Item was deleted' ];
        });
    }
    
    public function update(int $id, UpdateItem $request) {
        
        return DB::transaction(function() use($id, $request) {
            
            return $this->itemService->update(ItemMapper::toUpdate($id, $request));
        });
    }
    
    public function publish(UpdateItem $request) {
        $this->itemService->publish($request->route('id'));
        return [ 'message' => 'Item was published' ];
    }
    
}

