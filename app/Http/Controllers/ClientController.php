<?php
namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\StoreClient;
use App\Http\Requests\UpdaloadProfileImage;
use App\Mapper\UserMapper;
use App\Service\ClientService;
use App\Service\CurrentUserHandle;
use App\Http\Requests\SearchClient;

class ClientController
{
    /**
     * @var ClientService
     */
    private $clientService;
    
    public function __construct(ClientService $clientService) {
        
        $this->clientService = $clientService;
    }
    
    public function storeClient(StoreClient $request) : User {
        
        $user = UserMapper::fromRequest($request);
        
        $this->clientService->insert($user);
        
        return $user;
    }
    
    public function updaloadProfileImage(UpdaloadProfileImage $request) {
        
        return $this->clientService->updateImageProfile(
                    $request->file("file"), 
                    CurrentUserHandle::getUser()
                );
    }
    
    public function existsUsername(string $username) {
        return ['exists' => $this->clientService->findByUsername($username) != null];
    }
    
    public function existsEmail(string $email) {
        return ['exists' => $this->clientService->findByEmail($email) != null];
    }
    
    public function profile(string $username) {
        return $this->clientService->findClient($username);
    }
    
    public function myProfile() {
        return $this->clientService->findClient(CurrentUserHandle::getUser()->username);
    }
    
    public function searchByNameOrUsername(SearchClient $request) {
        return $this->clientService->searchByNameOrUsername($request->content);
    }
    
}

