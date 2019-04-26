<?php
namespace App\Mapper;

use App\DTOS\SearchFeedDTO;
use App\Http\Requests\SearchFeed;

class FeedMapper {
    
    public static function mapToSearch(SearchFeed $request): SearchFeedDTO {
        return new SearchFeedDTO(
            $request->content
            );
    }
    
}