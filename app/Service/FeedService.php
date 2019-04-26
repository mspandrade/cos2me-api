<?php
namespace App\Service;

use App\Item;
use App\ListResource;
use App\User;
use App\DTOS\SearchFeedDTO;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Character;
use App\Type\ResourceType;
use App\Mapper\TypeMapper;

class FeedService
{
    private $listService;
    private $itemService;
    
    public function __construct(ListService $listService, ItemService $itemService) {
        $this->listService = $listService;
        $this->itemService = $itemService;
    }
    
    public function byUser(string $username, $showPrivateItems = false) {
        
        $this->getMinimumAge();
        
        $user = User::where('username', $username)->first();
        
        if ($user == null) {
            abort(404, 'User not found');
        }
        
        $itemQuery = $this->itemQuery()->where('user_id', $user->id)
                                       ->orderBy(Item::POSTED_AT, 'desc');
        
        
        $listQuery = $this->listQuery()
                          ->where('user_id', $user->id)
                          ->union($itemQuery)
                          ->orderBy(Item::POSTED_AT, 'desc');

        return $this->transformPageOfItemsLists($listQuery->paginate(10));
    }
    
    public function me(User $user) {
        return $this->byUser($user->username);
    }
    
    public function searchByHashtags(SearchFeedDTO $dto) {
        
        $hashtags = $this->extractHashTags($dto->getContent());
        
        $listQuery = $this->whereHashTags($hashtags, $this->listQuery())
                        ->union($this->whereHashTags($hashtags, $this->itemQuery()))
                        ->orderBy(Item::POSTED_AT, 'desc');
        
        return $this->transformPageOfItemsLists($listQuery->paginate(3));
    }
    
    public function searchByCharacter(SearchFeedDTO $dto) {
        
        $character = Character::findOrFail((int) $dto->getContent());
        
        return $this->listService->feedQuery()->whereHas('character', function($q) use ($character) {
            
            $q->where('base_character_id', $character->id)
            ->orWhere('id', $character->id);
            
        })->orderBy(Item::POSTED_AT, 'desc')
          ->paginate(10);
    }
    
    private function transformPageOfItemsLists($page) {
        
        $itemsId = TypeMapper::mapId($page->getCollection(), ResourceType::ITEM);
        $listsId = TypeMapper::mapId($page->getCollection(), ResourceType::LIST);
        
        $lists = $this->listService->getByIds($listsId->toArray())->getDictionary();
        $items = $this->itemService->getByIds($itemsId->toArray())->getDictionary();
        
        $page->getCollection()->transform(function($value) use($items, $lists){
            
            $element = $value->type == 0 ? $items[$value->id] : $lists[$value->id];
            $element->type = $value->type;
            
            return $element;
        });
        
        return $page;
    }
    
    private static function extractHashTags($hashtags) {
        return array_map(function($hashtag) {
            
            return strtolower(trim($hashtag));
            
        },explode(',', $hashtags));
    }
    
    private function whereHashTags(array $hashtags, $query) {
        return $query->whereHas('hashtags', function($q) use ($hashtags) {
            
            $q->whereIn('content', $hashtags);
        });
    }
    
    private function getMinimumAge() {
        $user = CurrentUserHandle::getUser();
        return $user == null ? 12 : Carbon::parse($user->date_birth)->age;
    }
    
    private function listQuery() {
        return ListResource::select(
            'id',
            'created_at',
            'minimum_age',
            DB::raw("null as description"),
            DB::raw("1 as type"),
            DB::raw("null as minimum_price"),
            DB::raw("null as maximum_price"),
            ListResource::POSTED_AT,
            'character_id'
            )
            ->whereNotNull(ListResource::POSTED_AT)
            ->where('minimum_age', '<=', $this->getMinimumAge());
    }
    
    private function itemQuery() {
        
        return Item::select(
            'id',
            'created_at',
            DB::raw("12 as minimum_age"),
            'description',
            DB::raw("0 as type"),
            'minimum_price',
            'maximum_price',
            Item::POSTED_AT,
            DB::raw("null as character_id")
            )
            ->whereNotNull(Item::POSTED_AT);
    }
    
}

