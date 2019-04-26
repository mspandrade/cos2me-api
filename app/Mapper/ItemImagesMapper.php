<?php
namespace App\Mapper;

use App\DTOS\UpdateImagesItemDTO;
use App\Http\Requests\UpdateImagesItem;
use App\Service\CurrentUserHandle;

class ItemImagesMapper {
    
    public static function toUpdateImages(int $id, UpdateImagesItem $request) {
        return new UpdateImagesItemDTO(
                $id,
                CurrentUserHandle::getUser(),
                $request->file('files') ?: [],
                $request->removes ?: [],
                $request->order ?: []
            );
    }
    
}