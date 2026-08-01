<?php

use App\Models\Admin;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

if(!function_exists('getPersianModuleCaptions'))
{
    function getPersianModuleCaptions($slug) : string{
        return match($slug)
        {
            'branches' => 'شعبه ها',
            'categories' => 'دسته بندی ها',
            'services' => 'سرویس ها' , 
            'employees' => 'کارمندان',
            'reserves' => 'نوبت ها',
            'payments' => 'پرداخت ها ها' , 
            'new-reserve' => 'نوبت جدید',
            'customers' => ' مشتریان'
        };

        
    }

}

if(!function_exists('getPersianModuleCaptionButtons'))
{
     function getPersianModuleCaptionCreateButtons($slug) {


        switch(request()->segment(1)){
            case 'branches' :return 'شعبه جدید';break;
            case 'categories'  :return  'دسته بندی جدید';break;
            case 'services' :return 'سرویس جدید';break;
            case 'employees'  :return 'کارمند جدید';break;
            case 'reserves'  :return 0;break;
            case 'payments'  :return 0;break;
            case 'new-reserve' :return 0;break;
            case 'customers'  :return 0;break;
            default :return 0;

        }


    
    }
}


if(!function_exists('panelID'))
{
    function panelID(User $user){

       return $user->admin->panel->id;
    }
}


if(!function_exists('userAUTH')){

    function userAUTH(){
        $user = Auth::user();
        return $user;
    }
}


if(!function_exists('set_first_branch')){

    function set_first_branch(Admin $admin){
    if (!app()->runningInConsole()) {
        $branch = optional($admin->panel)->branches?->first();
        session(['current_branch' => $branch]);
    }
}

}


if(!function_exists('current_branch')){

function current_branch(): ?Branch
{
    if (app()->runningInConsole()) {
        return null;
    }

    if (!Schema::hasTable('branches')) {
        return null;
    }

    return session('current_branch')
        ?? Auth::user()?->admin?->panel?->branches?->first();
}

}


if(!function_exists('set_current_branch')){

    function set_current_branch(Branch $branch){
        session(['current_branch' => $branch]);
    }

}

if(!function_exists('farsi_week_days')){

    function farsi_week_days(){
        return [
            'شنبه' ,
            'یکشنبه','دوشنبه','سه شنبه','چهارشنبه','پنج شنبه','جمعه'
            
        ];
    } 
}