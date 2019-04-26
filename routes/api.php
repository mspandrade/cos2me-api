<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function() {
   
    return [
        'isAlive' => true,
        'version' => '1.3.0'
    ];
});

Route::post('clients', 'ClientController@storeClient');

Route::get('images/{uui}', 'ImageController@render');

Route::get('clients/{username}/username', 'ClientController@existsUsername');

Route::get('clients/{email}/email', 'ClientController@existsEmail');

Route::get('items/{id}', 'ItemController@find');

Route::prefix('lists')->group( function() {
    

    Route::get('{id}', 'ListController@findById');
    
    Route::get('{listId}/commentaries', 'CommentaryListController@commentariesOfList');
    
    
});

Route::get('feed/{username}', 'FeedController@feed');

Route::prefix('search')->group( function() {
    
    Route::get('hashtags', 'FeedController@searchByHashtags');
    
    Route::get('characters', 'FeedController@searchByCharacter');
    
    Route::get('clients', 'ClientController@searchByNameOrUsername');
});

Route::get('profile/{username}', 'ClientController@profile');

Route::middleware(['client'])->group( function() {
    
    Route::prefix('me')->group( function() {
        
        Route::get('', function() {
            return auth()->guard('api')->user();
        });
        
        Route::get('feed', 'FeedController@me');
        
        Route::get('profile', 'ClientController@myProfile');
        
    });
    
    Route::post('clients/image', 'ClientController@updaloadProfileImage');
    
    Route::prefix('items')->group( function() {
        
        Route::post('', 'ItemController@store');
        
        Route::post('{id}/images', 'ItemController@updateImagesItem');
        
        Route::delete('{id}', 'ItemController@delete');
        
        Route::put('{id}', 'ItemController@update');
        
        Route::put('{id}/publish', 'ItemController@publish');
        
        Route::post('{itemId}/ratings', 'RatingController@store');
        
    });
    
    Route::prefix('lists')->group( function() {
       
        Route::post('', 'ListController@store');
        
        Route::post('{id}/images', 'ListController@updateImage');
        
        Route::delete('{id}', 'ListController@delete');
        
        Route::put('{id}', 'ListController@update');
        
        Route::put('{id}/publish', 'ListController@publish');
        
        Route::post('{listId}/commentaries', 'CommentaryListController@store');
        
        Route::post('{listId}/likes', 'LikeController@store');
        
        Route::delete('{listId}/likes', 'LikeController@delete');
        
    });

    Route::prefix('commentaries')->group( function() {
        
        Route::put('{id}', 'CommentaryListController@update');
        
        Route::delete('{id}', 'CommentaryListController@delete');
        
    });
    
    Route::prefix('ratings')->group( function() {
        
        Route::put('{id}', 'RatingController@update');
        
        Route::delete('{id}', 'RatingController@delete');
        
    });
    
    Route::prefix('follow')->group( function() {
        
        Route::post('{id}', 'FollowerController@follow');
        
        Route::delete('{id}', 'FollowerController@unfollow');
        
    });
    
    Route::prefix('inventories')->group( function() {
        
        Route::get('', 'InventoryController@showInventory');
        
        Route::post('items/{id}', 'InventoryController@storeItem');
        
        Route::post('lists/{id}', 'InventoryController@storeListToInventory');
        
        Route::get('lists/{id}', 'InventoryController@showInventoryList');
        
        Route::get('items/{id}', 'InventoryController@showInventoryItem');
        
        Route::patch('items/{id}/complete', 'InventoryController@completeItem');
        
        Route::patch('items/{id}/uncomplete', 'InventoryController@uncompleteItem');
        
        Route::patch('steps/{id}/complete', 'InventoryController@completeStep');
        
        Route::patch('steps/{id}/uncomplete', 'InventoryController@uncompleteStep');
        
    });
    
});