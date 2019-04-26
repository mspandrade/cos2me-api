<?php
namespace App\DTOS;

use App\ListResource;
use Illuminate\Http\UploadedFile;

class StoreListImageDTO {
    
    private $file;
    private $list;
    private $order;
    private $isReference;
    
    public function __construct(
        UploadedFile $file, 
        ListResource $list, 
        int $order, 
        bool $isReference
        ) {
        $this->file = $file;
        $this->list = $list;
        $this->order = $order;
        $this->isReference = $isReference;
    }

    public function getFile(): UploadedFile {
        return $this->file;
    }

    public function getList(): ListResource {
        return $this->list;
    }

    public function getOrder(): int {
        return $this->order;
    }

    public function getIsReference(): bool {
        return $this->isReference;
    }

}