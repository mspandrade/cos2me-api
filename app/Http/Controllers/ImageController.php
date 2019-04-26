<?php
namespace App\Http\Controllers;

use App\Service\ImageService;

class ImageController
{
    
    /**
     * 
     * @var ImageService
     */
    private $imageService;
    
    public function __construct(ImageService $imageService) {
        $this->imageService = $imageService;
    }
    
    public function render(string $uuid) {
        
        return response($this->imageService->stream($uuid), 200, ['Content-Type' => 'image/jpeg']);
    }
    
}

