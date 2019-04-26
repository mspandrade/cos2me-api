<?php

namespace App\Providers;

use App\Service\CurrentUserHandle;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        
        Carbon::setLocale(config('app.locale'));
        
        $this->registerValidations();
    }
    
    private function registerValidations() {
        
        Validator::extend('owner', function($attribute, $value, $parameters) {
            
            $userId = CurrentUserHandle::getUser()->id;
            
            $table = $parameters[0];
            $columnObjectId = $parameters[1] ?? 'id';
            $columnUserId = $parameters[2] ?? 'user_id';
            
            $result = DB::select("SELECT $columnObjectId FROM {$table} WHERE {$columnObjectId}=? AND {$columnUserId}=? LIMIT 1",[
                $value,
                $userId
            ]);
            
            return !empty($result);
            
        }, 'Only owner can edit it');
            
        Validator::extend('postedOrOwner', function($attribute, $value, $parameters) {
            
            $table = $parameters[0];
            $columnObjectId = $parameters[1] ?? 'id';
            $postedAtColumn = $parameters[2] ?? 'posted_at';
            $columnUserId = $parameters[3] ?? 'user_id';
            
            $result = DB::select("SELECT $columnObjectId FROM {$table} WHERE {$columnObjectId}=? AND ({$postedAtColumn} IS NOT NULL OR {$columnUserId} = ?) LIMIT 1",[
                $value,
                CurrentUserHandle::getUser()->id
            ]);
            
            return !empty($result);
            
        }, 'Resource not found');
    }
}
