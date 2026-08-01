<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $table ='payments'; 
    protected $fillable = [ 
        'reff',
        'branch_id',
        'user_id' , 
        'amount'
    ];
}
