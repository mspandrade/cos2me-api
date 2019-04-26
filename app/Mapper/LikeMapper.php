<?php
namespace App\Mapper;

use App\DTOS\StoreLikeDTO;
use App\Service\CurrentUserHandle;
use Illuminate\Http\Request;

class LikeMapper {
    
    public static function mapToInsert(Request $request): StoreLikeDTO {
        return new StoreLikeDTO(
            CurrentUserHandle::getUser(), 
            $request->route('listId')
            );
    }
}