<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model {
    
    public $timestamps = false;
    
    protected $fillable = [
        'user_id', 'follower_id'
    ];
    
}