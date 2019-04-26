<?php
namespace App;

class ItemHasHashtag
{
    protected $table = 'items_has_hashtags';
    
    protected $fillable = [
        'item_id', 'hashtag_id'
    ];
    
}

