<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class authenticationController extends Controller
{
    //

    public function auth_sms() //authentication by sms and mobile
    {}
    public function view_auth_sms()
    {
        // $user = new User();
        // $user->id = 1 ;
        // AdminController::has_admin($user);
        return 'view_auth_sms';
    }
}
