<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Material extends Model {
    
    public $timestamps = false;
    
    protected $fillable = [ 'content' ];
    
    protected $hidden = [ 'id', 'item_id' ];
    
}