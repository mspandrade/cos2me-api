<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryItemStep extends Model {
    
    public $timestamps = false;
    
    protected $tablename = 'inventory_item_steps';
    
    protected $fillable = [
        'description',
        'order',
        'inventory_item_id',
        'is_complete'
    ];
    
    public function inventoryItem() {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id', 'id');
    }
    
    public function step() {
        return $this->belongsTo(Step::class);
    }
    
    
}