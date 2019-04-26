<?php
namespace App\Service;

use Faker\Provider\Uuid;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    private const DISK_NAME = 's3';
    
    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    private $disk;
    
    public function __construct() {
        $this->disk = Storage::disk(self::DISK_NAME);
    }
    
    public function insert(UploadedFile $file) : string {
        
        $uuid = Uuid::uuid();
        $file->storeAs('', $uuid, self::DISK_NAME);
        
        return $uuid;
    }
    
    public function delete(string $uuid) {
        Storage::disk(self::DISK_NAME)->delete($uuid);
    }
    
    public function update(UploadedFile $file, string $oldUuid = null) {
        
        $uuid = $this->insert($file);
        
        if ($oldUuid != null) {
            $this->delete($oldUuid);
        };
        return $uuid;
    }
    
    public function stream(string $uuid) {
        
        if (!$this->disk->exists($uuid)) {
            abort(404, 'File not found');
        }
        return $this->disk->get($uuid);
    }
    
}

