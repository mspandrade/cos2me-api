<?php
namespace App\DTOS;

use App\User;

class StoreCommentaryListDTO {
    
    private $content;
    private $user;
    private $listId;
    
    public function __construct(
        int $listId,
        User $user,
        string $content
        ) {
        $this->content = $content;
        $this->user = $user;
        $this->listId = $listId;
    }
    

    public function getContent(): string {
        return $this->content;
    }

    public function getUser(): User {
        return $this->user;
    }

    public function getListId(): int {
        return $this->listId;
    }
    
    public function getFillData() {
        return [
            'user_id'   => $this->user->id,
            'list_id'   => $this->listId,
            'content'   => $this->content
        ];
    }

}