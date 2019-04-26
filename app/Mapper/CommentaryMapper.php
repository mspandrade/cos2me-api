<?php
namespace App\Mapper;

use App\Http\Requests\UpdateCommentary;
use App\Service\CurrentUserHandle;
use App\DTOS\StoreCommentaryListDTO;
use App\DTOS\UpdateCommentaryListDTO;
use App\Http\Requests\StoreCommentary;

class CommentaryMapper {
    
    public static function mapToInsert(StoreCommentary $request): StoreCommentaryListDTO {
        return new StoreCommentaryListDTO(
            $request->route('listId'),
            CurrentUserHandle::getUser(),
            $request->content
            );
    }
    
    public static function mapToUpdate(UpdateCommentary $request): UpdateCommentaryListDTO  {
        return new UpdateCommentaryListDTO(
            $request->route('id'), 
            $request->content
            );
    }
    
}