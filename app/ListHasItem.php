<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ListHasItem extends Model {
    
    public $timestamps = false;
    
    protected $table = 'lists_has_items';
    
    protected $fillable = [
        'is_complete', 'list_id', 'item_id'
    ];
    
}