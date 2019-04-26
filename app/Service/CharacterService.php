<?php
namespace App\Service;

use App\Character;

class CharacterService
{
    
    public function findById(int $id) {
        return Character::findOrFail($id); 
    }
    
    public function createVersion(string $name): Character {
        
        $character = Character::where('name', 'like', $name)->first();
        
        if ($character == null) {
            
            $character = Character::create([
                'name'      => $name,
                'is_base'   => false
            ]);
        }
        return $character;
    }
    
}

