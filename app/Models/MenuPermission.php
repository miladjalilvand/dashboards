<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPermission extends Model
{
    //
    protected $table ='menu_permissions';
    protected $fillable = [ 'permission_id' , 'menu_id'];
}
