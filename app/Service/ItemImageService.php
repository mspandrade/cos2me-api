<?php
namespace App\Service;


use App\Item;
use App\ItemImage;
use App\DTOS\StoreItemImageDTO;

class ItemImageService
{
    private $imageService;
    
    public function __construct(ImageService $imageService) {
        $this->imageService = $imageService;
    }
    
    public function delete(array $uuids) {
        
        $images = ItemImage::whereIn('uuid', $uuids)->get();
        
        foreach ($images as $image) {
            
            $this->imageService->delete($image->uuid);
            $image->delete();
        }
    }
    
    public function insert(StoreItemImageDTO $dto): ItemImage {
        
        $uuid = $this->imageService->insert($dto->getFile());
        
        return ItemImage::create([
           'item_id'    => $dto->getItem()->id,
           'uuid'       => $uuid,
           'order'      => $dto->getOrder()
        ]);
    }
    
    public function findByUuid($uuid) {
        return ItemImage::where(['uuid' => $uuid])->first();
    }
    
    public function countByItem(Item $item): int {
        return ItemImage::where(['item_id' => $item->id])->count();
    }
    
}

