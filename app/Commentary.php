<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Commentary extends Model {
    
    protected $tablename = 'commentaries';
    
    protected $fillable = [
        'list_id', 'user_id', 'content'
    ];
    
    protected $hidden = [];
    
    public function list() {
        return $this->belongsTo(ListResource::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
}