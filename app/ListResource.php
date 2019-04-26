<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListResource extends Model {
    
    use SoftDeletes;
    
    const POSTED_AT = 'posted_at';
    const LIST_ID = 'list_id';
    
    protected $table = 'lists';
    
    protected $fillable = [
        'posted_at', 'minimum_age', 'user_id', 'character_id'
    ];
    
    protected $hidden = [
        'deleted_at'
    ];
    
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function hashtags() {
        return $this->belongsToMany(
            Hashtag::class,
            'lists_has_hashtags',
            ListResource::LIST_ID,
            'hashtag_id'
            );
    }
    
    public function items() {
        return $this->belongsToMany(
            Item::class,
            'lists_has_items',
            ListResource::LIST_ID,
            'item_id'
            );
    }
    
    public function inventoryItems() {
        return $this->hasMany(InventoryItem::class, ListResource::LIST_ID);
    }
    
    public function inventoryItemsComplete() {
        return $this->hasMany(InventoryItem::class, ListResource::LIST_ID)->where('is_complete', true);
    }
    
    public function inventorySteps() {
        return $this->hasManyThrough(InventoryItemStep::class, InventoryItem::class, ListResource::LIST_ID, 'inventory_item_id');
    }
    
    public function inventoryStepsComplete() {
        return $this->hasManyThrough(
            InventoryItemStep::class, 
            InventoryItem::class, 
            ListResource::LIST_ID, 
            'inventory_item_id'
            )
        ->where('inventory_item_steps.is_complete', true);
    }
     
    public function commentaries() {
        return $this->hasMany(Commentary::class, ListResource::LIST_ID);
    }
    
    public function likes() {
        return $this->hasMany(Like::class, ListResource::LIST_ID);
    }
    
    public function character() {
        return $this->belongsTo(Character::class);
    }
    
    public function images() {
        return $this->hasMany(ListImage::class, ListResource::LIST_ID);
    }
    
    public function wasPublished() {
        return $this->posted_at != null;
    }
    
}