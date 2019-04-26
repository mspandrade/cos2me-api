<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use SoftDeletes;
    
    const POSTED_AT = 'posted_at';
    
    protected $fillable = [
        'user_id', 'description', 'posted_at', 'tutorial', 
        'video_tutorial', 'minimum_price', 'maximum_price'
    ];
    
    protected $hidden = [
        'deleted_at'
    ];
    
    public function setUser(User $user) {
        $this->user_id = $user->id;
    }
    
    public function steps() {
        return $this->hasMany(Step::class);
    }
    
    public function images() {
        return $this->hasMany(ItemImage::class);
    }
    
    public function itemImages() {
        return $this->hasMany(ItemImage::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function materials() {
        return $this->hasMany(Material::class);
    }
    
    public function hashtags() {
        return $this->belongsToMany(
            Hashtag::class, 
            'items_has_hashtags',
            'item_id',
            'hashtag_id'
            );
    }   
    
    public function ratings() {
        return $this->hasMany(Rating::class);
    }
    
    public function resumeRatings() {
        return $this->ratings()->limit(5);
    }
    
    public function wasPublished() {
        return $this->posted_at != null;
    }
    
}

