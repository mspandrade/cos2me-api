<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Mpociot\HasCompositeKey\HasCompositeKey;

class Like extends Model {
    
    use HasCompositeKey;
    
    protected $fillable = [
        'list_id', 'user_id'
    ];
    
    protected $primaryKey = ['list_id', 'user_id'];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function list() {
        return $this->belongsTo(ListResource::class);
    }
    
}