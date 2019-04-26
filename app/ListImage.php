<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListImage extends Model
{
    public $timestamps = false;
    
    protected $table = 'list_images';
    
    protected $fillable = [
      'list_id', 'uuid', 'order', 'is_reference'
    ];
    
    protected $hidden = [
        'list_id', 'id'
    ];
}

