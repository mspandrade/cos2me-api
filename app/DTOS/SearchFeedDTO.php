<?php
namespace App\DTOS;

class SearchFeedDTO {
    
    private $content;
    
    public function __construct(string $content) {
        $this->content = $content;
    }
    
    public function getContent(){
        return $this->content;
    }

}