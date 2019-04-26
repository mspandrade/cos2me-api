<?php
namespace App\DTOS;

use App\Item;


class StoreStepDTO {
    
    private $description;
    private $item;
    private $order;
    
    public function __construct(
        string $description,
        Item $item,
        int $order
        ) {
        $this->description = $description;
        $this->item = $item;
        $this->order = $order;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getItem(): Item {
        return $this->item;
    }

    public function getOrder(): int {
        return $this->order;
    }

}