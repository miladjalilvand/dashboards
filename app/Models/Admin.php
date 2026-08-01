<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    //
    protected $table = 'admins';
    protected $filleble = [ 
    'user_id',
    'panel_id',
    'role_id',
    'is_active' , 'password' ];

     protected $hidden = [
        'password',
    ];

    public function panel () 
    {
        return $this->belongsTo(Panel::class);
    }



    public function user ()
    {
        return $this->belongsTo(User::class);
    }
}
