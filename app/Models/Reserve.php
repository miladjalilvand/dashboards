<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    //
    protected $table = 'reserves';
    protected $fillable = [
        'total_time' ,
        'discount' ,
        'total_cost' ,
        'end_time' ,
        'start_time' ,
        'customer_id' ,
        'branch_id' ,
        'status_id',
        'date',
        'employee_id'
    ];


    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }
    public function branch(){
        return $this->belongsTo('App\Models\Branch');
    }
    public function status(){
        return $this->belongsTo('App\Models\Status');
    }
    public function employee(){
        return $this->belongsTo('App\Models\Employee');
    }

    public function payments()
    {
        return $this->belongsToMany(
            Payment::class,
            'reserve_payments',
            'reserve_id',
            'payment_id'
        );
    }


    public function scopePendingReseerves(){

    }

    public function scopeSubmittedReseerves(){

    }
    public function scopeFutureReseerves(){

    }
    public function scopePastReseerves(){

    }
    public function scopeTodayReseerves(){

    }
    public function scopeUserReseerves(){

    }


    public function changeStatus(){

    }
}
