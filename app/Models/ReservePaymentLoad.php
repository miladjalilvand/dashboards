<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservePaymentLoad extends Model
{
    protected $table = 'reserve_payment_loads';

    protected $fillable = [
        'authority',

        'branch_id',
        'customer_id',
        'employee_id',
        'service_id',

        'start_time',
        'end_time',

        'total_cost',
        'discount',
        'total_time',

        'date',

        'payment_amount',

        'status',

        'description',
        'response',
    ];

    protected $casts = [
        'response' => 'array',
        'date' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(
            Branch::class
        );
    }

    public function customer()
    {
        return $this->belongsTo(
            Customer::class
        );
    }

    public function employee()
    {
        return $this->belongsTo(
            Employee::class
        );
    }

    public function service()
    {
        return $this->belongsTo(
            Service::class
        );
    }
}
