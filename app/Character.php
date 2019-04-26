<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model {
    
    protected $fillable = [
        'name', 'is_base', 'base_character_id'
    ];
    
    protected $hidden = [ 
        'is_base'
    ];
    
    public $timestamps = false;
    
    public function setBaseCharacter(Character $base) {
        $this->base_character_id = $base->id;
    }
    
    public function base() {
        return $this->belongsTo(Character::class, 'id', 'base_character_id');
    }
    
    public function versions() {
        return $this->hasMany(Character::class, 'base_character_id', 'id');
    }
    
}