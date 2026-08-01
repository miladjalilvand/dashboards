<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    //
    protected $table = 'branches';
    protected $fillable = [
        'caption',
        'phone',
        'mobile',
        'address',
        'location',
        'working_times','panel_id' , 'is_active'
    ];

    public function services (){
        return $this->hasMany(\App\Models\Service::class);
    }

    public function categories (){
        return $this->hasMany(\App\Models\Category::class);
    }
    public function employees (){
        return $this->hasMany(\App\Models\Employee::class);
    }
    public function reserves (){
        return $this->hasMany(\App\Models\Reserve::class);
    }

    public function customers (){
        return $this->hasMany(\App\Models\Customer::class);
    }

    public function payments (){
        return $this->hasMany(Payment::class);
    }

}
