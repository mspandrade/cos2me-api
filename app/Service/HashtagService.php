<?php

namespace App\Service;

use App\Hashtag;

class HashtagService {
    
    public function ids(array $hashtags) : array {
        
        $hashTagIds = [];
        
        foreach ($hashtags as $h) {
            
            $hashtag = strtolower(trim($h));
            
            $hashTagEntity = Hashtag::where('content', '=', $hashtag)->first();
            
            if ($hashTagEntity == null) {
                
                $hashTagEntity = Hashtag::create(['content' => $hashtag]);
            }
            $hashTagIds[] = $hashTagEntity->id;
        }
        return $hashTagIds;
    }
    
}