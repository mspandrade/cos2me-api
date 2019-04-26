<?php
namespace App\Service;


use App\ListImage;
use App\ListResource;
use App\DTOS\StoreListImageDTO;

class ListImageService
{
    private $imageService;
    
    public function __construct(ImageService $imageService) {
        $this->imageService = $imageService;
    }
    
    public function delete(array $uuids) {
        
        $images = ListImage::whereIn('uuid', $uuids)->get();
        
        foreach ($images as $image) {
            
            $this->imageService->delete($image->uuid);
            $image->delete();
        }
    }
    
    public function insert(StoreListImageDTO $dto): ListImage {
        
        $uuid = $this->imageService->insert($dto->getFile());
        
        return ListImage::create([
           'list_id'        => $dto->getList()->id,
           'uuid'           => $uuid,
           'order'          => $dto->getOrder(),
           'is_reference'   => $dto->getIsReference()
        ]);
    }
    
    public function findByUuid($uuid) {
        return ListImage::where(['uuid' => $uuid])->first();
    }
    
    public function countByList(ListResource $list): int {
        return ListImage::where(['list_id' => $list->id])->count();
    }
    
}

