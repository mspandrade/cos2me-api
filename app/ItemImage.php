<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    public $timestamps = false;
    
    protected $table = 'item_images';
    
    protected $fillable = [
      'item_id', 'uuid', 'order'
    ];
    
    protected $hidden = [
        'item_id', 'id'
    ];
}

