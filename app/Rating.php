<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model {
    
    protected $fillable = [
        'item_id', 'value', 'description', 'user_id'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
}