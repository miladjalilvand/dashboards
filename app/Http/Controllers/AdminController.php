<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    // public static function has_admin(User $user) // when user has admin then redirect panel_admin else create_account 
    // {
    //     $exist_panel_admin = Admin::where('user_id' ,1)->first();

    //     if($exist_panel_admin) {
    //         dd($exist_panel_admin->panel);
    //     }
    //     else {
    //         dd('nis');
    //         //redirect to create admin and panel 
    //     }

        
    // }

    public function create() 
    {}

    public function store() 
    {}


    public function verify_password(Request $request , User $user){

        $verify_admin = Admin::where('user_id' , $user->id)->
        where('password' , $request->input('password'))->first();

        if($verify_admin){

            set_first_branch($verify_admin);
            
            //return view panel

        }
    }
}
