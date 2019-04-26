<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model {
    
    protected $tablename = 'inventory_items';
    
    protected $fillable = [
        'is_complete', 'item_id', 'user_id', 'list_id'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function item() {
        return $this->belongsTo(Item::class);
    }
    
    public function steps() {
        return $this->hasMany(InventoryItemStep::class, 'inventory_item_id', 'id');
    }
    
    public function stepsComplete() {
        return $this->hasMany(InventoryItemStep::class, 'inventory_item_id', 'id')->where('is_complete', true);
    }
    
}