<?php
namespace App\DTOS;

use App\User;

class UpdateImagesItemDTO
{
    private $id;
    private $user;
    private $files = [];
    private $removes = [];
    private $order = [];
    
    public function __construct(
        int $id, 
        User $user, 
        array $files, 
        array $removes,
        array $order
        ) {
        $this->files = $files;
        $this->removes = $removes;
        $this->id = $id;
        $this->user = $user;
        $this->order = $order;
    }
    

    public function getRemoves(): array {
        return $this->removes;
    }

    /**
     * 
     * @return \Illuminate\Http\UploadedFile[]
     */
    public function getFiles(): array {
        return $this->files;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUser(): User {
        return $this->user;
    } 
    
    public function getOrder(): array {
        return $this->order;
    }

}

