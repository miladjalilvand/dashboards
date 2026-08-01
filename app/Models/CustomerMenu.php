<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerMenu extends Model
{
    //

    protected $table = "customer_menus"; 

         protected $fillable = ["visible" , "caption" ,"menu_type_id", "slug"];

    public function MenuType () {
        return $this->belongsTo(MenuType::class);
    }
}
