<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
    
    public const USERNAME = 'usernme';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username', 'date_birth'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 
        'email', 'id', 'deleted_at', 
        'created_at', 'updated_at', 
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    
    public function follower() {
        return $this->belongsToMany(
            User::class,
            Follower::class,
            'user_id',
            'follower_id'
            );
    }
    
    public function following() {
        return $this->belongsToMany(
            User::class,
            Follower::class,
            'follower_id',
            'user_id'
            );
    }
    
    public function publicLists() {
        return $this->hasMany(ListResource::class)->whereNotNull('posted_at');
    }
    
    public function publicItems() {
        return $this->hasMany(Item::class)->whereNotNull('posted_at');
    }
    
}
