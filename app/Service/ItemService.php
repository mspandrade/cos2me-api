<?php
namespace App\Service;


use App\Item;
use App\DTOS\UpdateImagesItemDTO;
use App\DTOS\DeleteItemDTO;
use App\DTOS\StoreItemImageDTO;
use Illuminate\Http\JsonResponse;
use App\DTOS\StoreItemDTO;
use App\DTOS\StoreStepDTO;
use App\DTOS\UpdateItemDTO;
use Carbon\Carbon;
use App\Material;

class ItemService
{
    private $hashTagService;
    private $stepService;
    private $itemImageService;
    
    public function __construct(
        HashtagService $hashTagService,
        StepService $stepService,
        ItemImageService $itemImageService
        ) {
        
        $this->hashTagService = $hashTagService;
        $this->stepService = $stepService;
        $this->itemImageService = $itemImageService;
    }
    
    public function hasPrivateItems(array $ids) {
        return Item::whereIn('id', $ids )->whereNull(Item::POSTED_AT)->count() > 0;
    }
    
    public function insert(StoreItemDTO $dto) : Item {
        
        $hashtagEntities = $this->hashTagService->ids($dto->getHashTags());
        
        $item = Item::create($dto->getFillData());
            
        $item->hashtags()->attach($hashtagEntities);
        $this->addSteps($dto->getSteps(), $item);
        $this->addMaterials($item, $dto->getMaterials());
        
        return $item;
    }
    
    public function findById(int $id) {
        return Item::with('hashtags', 'steps', 'user:id,username,name,date_birth', 'images', 'materials')
             ->whereKey($id)    
             ->where(function($query) {
                 
                 $query->whereNotNull('posted_at');
                 
                 $user = CurrentUserHandle::getUser();
                  
                 if ($user != null) {
                     
                     $query->orWhere('user_id', $user->id);
                 }
                 
                 return $query;
                 
             })->first();
    }
    
    public function updateImages(UpdateImagesItemDTO $dto): array {
        
        $item = Item::findOrFail($dto->getId());
        
        $this->abortIfPosted($item);
        
        $this->itemImageService->delete($dto->getRemoves());
        
        $countImages = $this->itemImageService->countByItem($item) + count($dto->getFiles());
        
        if ($countImages != count($dto->getOrder())) {
            abort(JsonResponse::HTTP_BAD_REQUEST, 'Parameter order must has the same numbers of images');
        }
        
        $files = (new \ArrayObject($dto->getFiles()))->getIterator();
        
        $images = [];
        
        foreach ($dto->getOrder() as $key => $uuid) {
            
            if ($uuid != 'new') {
                
                $image = $this->itemImageService->findByUuid($uuid);
                $image->order = $key;
                $image->save();
                $images[] = $image;
                
            } else {
                
                $images[] = $this->itemImageService
                                 ->insert(new StoreItemImageDTO(
                                     $files->current(), 
                                     $item,
                                     $key
                                     ));
                                 
                $files->next();
            }
        }
        
        return $images;
    }
    
    public function delete(DeleteItemDTO $dto) {
        
        $item = Item::with('images')->findOrFail($dto->getId());
        
        $uuids = [];
        
        foreach ($item->images as $image) {
            $uuids[] = $image->uuid;
        }
        
        $this->itemImageService->delete($uuids);
        $item->steps()->delete();
        $item->hashtags()->detach();
        $item->delete();
    }
    
    public function update(UpdateItemDTO $dto) {
        
        $item = Item::findOrFail($dto->getId());
        
        $this->abortIfPosted($item);
        
        if ($dto->getDescription() != null) {
            $item->description = $dto->getDescription();
        }
        
        $item->hashtags()->sync($this->hashTagService->ids($dto->getHashTags()));
        
        if ($dto->getSteps() != null) {
            
            $item->steps()->delete();
            $this->addSteps($dto->getSteps(), $item);
        }
        
        $item->materials()->delete();
        $this->addMaterials($item, $dto->getMaterials());
        
        return $this->findById($dto->getId());
    }
    
    public function publish(int $id) {
        
        $item = Item::findOrFail($id);
        
        if ($item->posted_at != null) {
            abort(404, 'This item was already published');
        }
        
        if ($item->images()->count() <= 0) {
            abort(403, 'The item need at least one images');
        }
        
        $item->posted_at = Carbon::now();
        $item->save();
    }
    
    public function getByIds(array $ids) {
        return Item::with('images', 'hashtags', 'resumeRatings')
                ->select(
                    'id',
                    'created_at',
                    'description',
                    'minimum_price',
                    'maximum_price',
                    Item::POSTED_AT)    
                ->withCount('ratings')
                ->whereIn('id', $ids)
                ->get();
    }
    
    private function abortIfPosted(Item $item) {
        abort_if($item->posted_at != null, 403, 'You cannot edit a posted item');
    }
    
    private function addSteps(array $steps, Item $item) {
        foreach ($steps as $order => $step) {
            
            $this->stepService->insert(new StoreStepDTO(
                $step,
                $item,
                $order
                ));
        }
    }
    
    private function addMaterials(Item $item, array $contents) {
        $item->materials()->saveMany(array_map(function($content) {
            
            $material = new Material();
            $material->content = $content;
            
            return $material;
            
        }, $contents));
    }
}

