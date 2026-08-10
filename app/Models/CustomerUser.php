<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerUser extends Model
{
    //
    protected $table = 'customer_users';
    protected $fillable  = [
        'name',
        'mobile'
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class);

    }
}
