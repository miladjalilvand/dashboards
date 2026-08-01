<?php

namespace App\Providers;

use App\Models\Branch;
use Illuminate\Support\ServiceProvider;

class CurrentBranch extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        // $this->app->bind('current-branch' , function(){

        //     if (session()->has('branch-id')) {
        //         return [
        //             'result' => true,
        //             'current-branch'=>session('branch-id')];
        //     }

        //     return ['result' => false];
        // });

        $this->app->bind('show-menu-all' ,
    function(){
        return 0;
    }
    );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
         view()->share('show-menu-all', app('show-menu-all'));
    }
}
