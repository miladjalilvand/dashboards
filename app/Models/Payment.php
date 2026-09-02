<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [

        'amount',

        'authority',

        'status_code',

        'status',

        'ref_id',

        'description',

        'response',

        'paid_at',

        'method',
    ];

    protected $casts = [

        'response' => 'array',

        'paid_at' => 'datetime',
    ];

    public function reserves()
    {
        return $this->belongsToMany(
            Reserve::class,
            'reserve_payments',
            'payment_id',
            'reserve_id'
        );
    }
}
