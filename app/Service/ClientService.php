<?php
namespace App\Service;

use App\User;
use App\Type\Role;
use Illuminate\Http\UploadedFile;

class ClientService extends UserService
{
    /**
     * @var ImageService
     */
    private $imageService;
    
    public function __construct(ImageService $imageService) {
        $this->imageService = $imageService;
    }
    
    public function insert(User $user) : User {
        
        $user->role = Role::CLIENT;
        return parent::insert($user);
    }
    
    public function updateImageProfile(UploadedFile $file, User $user) : User {
        
        $user->profile_image = $this->imageService->update($file, $user->profile_image);
        $user->save();
        return $user;
    }
    
    public function findByUsername(string $username) {
        return User::where([User::USERNAME => $username])->first();
    }
    
    public function findByEmail(string $email) {
        return User::where(['email' => $email])->first();
    }
    
    public function findClient(string $username) {
        
        $user = User::withCount('follower', 'following', 'publicLists', 'publicItems')
                     ->where([ 
                         User::USERNAME => $username, 
                         'role'     => Role::CLIENT
                     ])
                     ->first();
        
                     
        if ($user == null) {
            abort(404, 'Client not found');
        }
        
        $currentUser = CurrentUserHandle::getUser();
        
        if ($currentUser != null && $currentUser->id != $user->id) {
            
            $user->is_following = $user->follower()
                                       ->where('follower_id', $currentUser->id)
                                       ->count() > 0;
        }
        return $user;
    }
    
    public function searchByNameOrUsername(string $value) {
        
        $searchValue = "%$value%";
        
        return User::where('role', Role::CLIENT)->where(function ($query) use($searchValue) {
            
            $query->where('name', 'like', $searchValue)
                  ->orWhere('username', 'like', $searchValue);
            
        })->orderBy('username', 'asc')
          ->paginate(10);        
    }
    
}

