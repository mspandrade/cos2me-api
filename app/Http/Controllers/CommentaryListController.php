<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentary;
use App\Http\Requests\UpdateCommentary;
use App\Mapper\CommentaryMapper;
use App\Service\CommentaryService;
use App\Http\Requests\DeleteCommentaryList;

class CommentaryListController {
    
    private $commentaryService;
    
    public function __construct(CommentaryService $commentaryService) {
        $this->commentaryService = $commentaryService;
    }
    
    public function store(StoreCommentary $request) {
        
        return $this->commentaryService->insert(CommentaryMapper::mapToInsert($request));
    }
    
    public function commentariesOfList(int $listId) {
        
        return $this->commentaryService->commentariesOfList($listId);
    }
    
    public function update(UpdateCommentary $request) {
        return $this->commentaryService->update(CommentaryMapper::mapToUpdate($request));
    }
    
    public function delete(DeleteCommentaryList $request) {
        $this->commentaryService->delete($request->route('id'));
        return [
            'message' => 'Commentary was deleted'
        ];
    }
    
}