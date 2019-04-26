<?php
namespace App\Service;

use App\ListResource;
use App\DTOS\StoreListDTO;
use App\DTOS\UpdateImagesListDTO;
use Illuminate\Http\JsonResponse;
use App\DTOS\StoreListImageDTO;
use App\DTOS\UpdateListDTO;
use Carbon\Carbon;
use App\User;
use App\DTOS\PublishListDTO;

class ListService {
    
    private $hashtagService;
    private $itemService;
    private $characterService;
    private $listImageService;
    private $inventoryService;
    
    public function __construct(
        HashtagService $hashtagService, 
        ItemService $itemService,
        CharacterService $characterService,
        ListImageService $listImageService,
        InventoryService $inventoryService
        ) {
        
        $this->hashtagService = $hashtagService;
        $this->itemService = $itemService;
        $this->characterService = $characterService;
        $this->listImageService = $listImageService;
        $this->inventoryService = $inventoryService;
    }
    
    public function insert(StoreListDTO $dto): ListResource {
        
        $characterId = $dto->getCharacter()->getId();
        
        if ($characterId == null) {
            
            $characterId = $this->characterService
                                ->createVersion($dto->getCharacter()->getName())
                                ->id;
        }
        
        $list = ListResource::create([
            'character_id'  => $characterId,
            'user_id'       => $dto->getUser()->id,
            'minimum_age'   => $dto->getMinimumAge()
        ]);
        
        $list->hashtags()->attach(
            $this->hashtagService->ids($dto->getHashtags())
            );
        
        $list->items()->attach($dto->getItems());
        
        $this->inventoryService->addListToInventory($list, $dto->getUser());
        
        return $list;
    }
    
    public function findById(int $id) {
        
        return ListResource::with('items.images','items.user', 'character', 'hashtags', 'user', 'images')
                ->withCount('commentaries')
                ->withCount('likes')
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
    
    public function updateImages(UpdateImagesListDTO $dto) {
        
        $item = ListResource::findOrFail($dto->getId());
        
        $this->listImageService->delete($dto->getRemoves());
        
        $countItems = $this->listImageService->countByList($item) + count($dto->getFiles());
        
        if ($countItems != count($dto->getOrder())) {
            abort(JsonResponse::HTTP_BAD_REQUEST, 'Parameter order must has the same numbers of images');
        }
        
        $files = (new \ArrayObject($dto->getFiles()))->getIterator();
        $references = (new \ArrayObject($dto->getReferences()))->getIterator();
        
        $images = [];
        
        foreach ($dto->getOrder() as $key => $uuid) {
            
            if ($uuid != 'new') {
                
                $image = $this->listImageService->findByUuid($uuid);
                $image->order = $key;
                $image->is_reference = (int) (bool) $references->current();
                $image->save();
                $images[] = $image;
                
            } else {
                
                $images[] = $this->listImageService
                ->insert(new StoreListImageDTO(
                    $files->current(),
                    $item,
                    $key,
                    $references->current()
                    ));
                
                $files->next();
            }
            $references->next();
        }
        
        return $images;
    }
    
    public function update(UpdateListDTO $dto) {
        
        $list = ListResource::with('items')->findOrFail($dto->getId());
        
        if ($dto->getItems() != null) {
            
            if ($list->wasPublished() and $this->itemService->hasPrivateItems($dto->getItems())) {
                abort(403, 'Cannot add private items in a public list ');   
            }
            
            $actualItemIds = $list->items->map(function($item) { return $item->id; } );
            $updatedItemIds = collect($dto->getItems());
            
            $this->inventoryService->removeItemsListByItemId(
                $actualItemIds->diff($updatedItemIds)->toArray(), 
                $list
                );
            
            $this->inventoryService->insertItemList(
                $updatedItemIds->diff($actualItemIds), 
                $list
                );
            
            $list->items()->sync($dto->getItems());
            
        }
        
        if ($dto->getHashtags() != null) {
            $list->hashtags()->sync(
                $this->hashtagService->ids($dto->getHashtags())
                );
        }
        
        if ($dto->getCharacter() != null && $dto->getCharacter()->getId() != null) {
            $this->characterService->createVersion($dto->getCharacter()->getName())->id;
        }
        
        if ($dto->getMinimumAge() != null) {
            $list->minimum_age = $dto->getMinimumAge();
        }
        
        $list->save();
        
        return $list;
    }
    
    public function publish(PublishListDTO $dto) {
        
        $list = ListResource::findOrFail($dto->getId());
        
        if ($list->wasPublished()) {
            abort(404, 'This list was already published');
        }
        
        if ( $list->items()->whereNull('posted_at')->count() > 0) {
            abort(403, 'This list has private items');
        }
        
        if ($list->images()->where('is_reference', true)->count() <= 0) {
            abort(403, 'The list need at least one reference images');
        }
        
        if ($list->images()->where('is_reference', true)->count() <= 0) {
            abort(403, 'The list need at least one result images');
        }
        
        $this->inventoryService->removeItemListByList($list);
        
        $list->posted_at = Carbon::now();
        $list->save();
    }
    
    public function delete(int $id) {
        $list = ListResource::findOrFail($id);
        $this->inventoryService->removeItemListByList($list);
        $list->delete();
    }
    
    public function feedQuery() {
        
        return ListResource::with('images', 'character', 'items', 'hashtags', 'items.images')
                    ->select(
                        'id',
                        'created_at',
                        'minimum_age',
                        'posted_at',
                        'character_id'
                        )
                    ->withCount('commentaries')
                    ->withCount('likes');
    }
    
    public static function inventoryQuery(User $user) {
        
        return ListResource::with('images', 'character', 'hashtags')
                ->select(
                    'id',
                    'created_at',
                    'minimum_age',
                    'posted_at',
                    'character_id')
                ->withCount('inventoryItems', 'inventoryItemsComplete', 'inventorySteps', 'inventoryStepsComplete')
                ->where('lists.user_id', $user->id);
    }
    
    public function getByIds(array $ids) {
        return $this->feedQuery()
                    ->whereIn('id', $ids)
                    ->get();
    }
    
}