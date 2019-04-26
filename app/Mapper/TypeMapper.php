<?php
namespace App\Mapper;

use Illuminate\Support\Collection;

class TypeMapper {
    
    public static function mapId(Collection $collection, int $type): Collection {
        
        return $collection->map(function($e) use ($type) {
            
            return $e->type == $type ? $e->id : null;
            
        })->reject(function ($e) {
            return $e == null;
        });
    }
    
}