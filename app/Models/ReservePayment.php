<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservePayment extends Model
{
    //
    protected $table ='reserve_payments'; 
    protected $fillable = [ 'reserve_id' , 'peyment_id'];
}
