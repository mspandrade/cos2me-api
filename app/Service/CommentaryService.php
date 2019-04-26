<?php
namespace App\Service;

use App\Commentary;
use App\ListResource;
use App\DTOS\StoreCommentaryListDTO;
use App\DTOS\UpdateCommentaryListDTO;

class CommentaryService {
    
    public function insert(StoreCommentaryListDTO $dto) {
        
        $list = ListResource::where('id', $dto->getListId())->whereNotNull('posted_at')->count();
        
        if ($list < 1) {
            abort(404, 'List not found');
        }
        return Commentary::create($dto->getFillData());
    }
    
    public function commentariesOfList(int $listId) {
        
        $list = ListResource::where('id', $listId)->whereNotNull('posted_at')->first();
        
        if ($list == null) {
            abort(404, 'List not found');
        }
        return $list->commentaries()->paginate(5);
    }
    
    public function update(UpdateCommentaryListDTO $dto) {
        $commentary = Commentary::findOrFail($dto->getId());
        $commentary->fill($dto->getFillData());
        $commentary->save();
        return Commentary::findOrFail($dto->getId());
    }
    
    public function delete(int $id) {
        Commentary::findOrFail($id)->delete();
    }
    
}