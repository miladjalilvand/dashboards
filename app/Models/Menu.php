<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //
     protected $fillable = ["visible" , "caption" ,"menu_type_id", "slug"];
    protected $table = "menus"; 

    public function MenuType () {
        return $this->belongsTo(MenuType::class);
    }
}
