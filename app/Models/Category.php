<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $table = 'categories';
    protected $fillable = [
        'branch_id' ,
        'caption' , 'is_active'
    ];


    public function services(){
        return $this->hasMany(Service::class);
    }
}
