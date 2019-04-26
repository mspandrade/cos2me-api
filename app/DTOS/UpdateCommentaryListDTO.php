<?php
namespace App\DTOS;


class UpdateCommentaryListDTO {
    
    private $id;
    private $content;
    private $user;
    
    public function __construct(
        int $id,
        string $content
        ) {
        $this->id = $id;
        $this->content = $content;
    }
    

    public function getContent(): string {
        return $this->content;
    }

    public function getId(): int {
        return $this->id;
    }
    
    public function getFillData() {
        return [
            'content'   => $this->content
        ];
    }

}