<?php
namespace App\DTOS;

use App\Item;
use Illuminate\Http\UploadedFile;

class StoreItemImageDTO {
    
    private $file;
    private $item;
    private $order;
    
    public function __construct(UploadedFile $file, Item $item, int $order) {
        $this->file = $file;
        $this->item = $item;
        $this->order = $order;
    }

    public function getFile(): UploadedFile {
        return $this->file;
    }

    public function getItem(): Item {
        return $this->item;
    }

    public function getOrder(): int {
        return $this->order;
    }

}