<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $table = 'customers';
    protected $fillable  = [
        'user_id',
//        'branch_id' ,
        'panel_id'
    ];


    public function user()
    {
        return $this->belongsTo(CustomerUser::class , 'user_id');
    }

    public function reserves (){
        return $this->hasMany(Reserve::class);
    }

    public function branchReserves($branchId)
    {
        return $this->reserves()
            ->where('branch_id', $branchId);
    }
}
