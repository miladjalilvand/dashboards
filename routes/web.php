<?php

use App\Livewire\branches\Create;
use App\Livewire\Faqs\Index;
use App\Livewire\Branch\Index as branch_index;
use App\Livewire\Branch\Edit as branch_edit;
use App\Livewire\Branch\Create as branch_create;
use App\Livewire\Category\Index as category_index;
use App\Livewire\Category\Edit as category_edit;
use App\Livewire\Category\Create as category_create;

use App\Livewire\Service\Index as service_index;
use App\Livewire\Service\Edit as service_edit;
use App\Livewire\Service\Create as service_create;
use App\Livewire\Employee\Index as employee_index;
use App\Livewire\Employee\Edit as employee_edit;
use App\Livewire\Employee\Create as employee_create;
use App\Livewire\Reserves\Index as reserves_index;
use App\Livewire\ReservesDashboard\Index as reserves_dashboard_index;
use App\Livewire\Customers\Index as customers_index;
use App\Livewire\Payments\Index as payments_index;



use App\Livewire\WebsiteLiveWire\Index as website_index;
//use App\Livewire\SiteLiveWire\Index as site;
use App\Livewire\SubWeb\Index as site;


use Illuminate\Support\Facades\Route;


Route::middleware(['auth' , 'utm'])->group(function(){
Route::get('/paymentsc', function () {
    return view('welcome');
})->name('paymentsc.index');
Route::get('/new-reservec', function () {
    return view('welcome');
})->name('new-reservec.index');
Route::get('/profile', function () {
    return view('welcome');
})->name('profile.index');
Route::get('/reservesc', function () {
    return view('welcome');
})->name('reservesc.index');

});
Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::view(uri: 'sample', view: 'sample')
    ->middleware(middleware: ['auth', 'verified'])
    ->name('sample');

        Route::middleware( ['auth', 'verified' ])->prefix('branches/')->name('branches.')->
    group(function(){
            Route::get('index' , branch_index::class)->name('index');
            Route::get('create' , branch_create::class)->name('create');
            Route::get('edit/{branch}' , branch_edit::class)->name('edit');
    });
    Route::middleware( ['auth', 'verified' , 'bre'])->group(function (){
    Route::prefix('reserves/')->name('reserves.')->
    group(function(){
            Route::get('index' , reserves_index::class)->name('index');


    });
    Route::prefix('reserves_dashboard/')->name('reserves_dashboard.')->
    group(function(){
            Route::get('index' , reserves_dashboard_index::class)->name('index');


    });

        Route::prefix('categories/')->name('categories.')->
    group(function(){
            Route::get('index' , category_index::class)->name('index');
            Route::get('create' , category_create::class)->name('create');
            Route::get('edit' , category_edit::class)->name('edit');
    });
        Route::prefix('services/')->name('services.')->
    group(function(){
            Route::get('index' , service_index::class)->name('index');
            Route::get('create' , service_create::class)->name('create');
            Route::get('edit' , service_edit::class)->name('edit');
    });
        Route::prefix('employees/')->name('employees.')->
    group(function(){
            Route::get('index' , employee_index::class)->name('index');
            Route::get('create' , employee_create::class)->name('create');
            Route::get('edit' , employee_edit::class)->name('edit');
    });

        Route::get('banner_edit' , function(){
            return 'banner edir';
        })->name('opt_banner.index');
        Route::get('opt_aboutus' , function(){
            return 'opt_aboutus edir';
        })->name('opt_aboutus.index');
        Route::get('samples' , function(){
            return 'samples edir';
        })->name('samples.index');
    });






Route::get('/dashboards',App\Livewire\Dashboard\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboards');

Route::view('dashboard-1', view: 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard-1');

        Route::get('menu_type1', Index::class)
    ->middleware(middleware: ['auth', 'verified'])
    ->name('menu_type1');

    //         Route::get('menu_type1/create', Create::class)
    // ->middleware(middleware: ['auth', 'verified'])
    // ->name('menu_type1.create');


    //auth middleware for customers

Route::get('new-reserve' , website_index::class)->name('new-reserve.index');
Route::domain('{website}.abc.test')->group(function () {
    Route::get('/12', Site::class);
});

Route::get('customers' , customers_index::class)->name('customers.index');
Route::get('payments' , payments_index::class)->name('payments.index');


require __DIR__.'/settings.php';

