<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';
    protected $fillable = [
        'description',
        'cost',
        'discount',
        'time',
        'caption',
        'category_id',
        'branch_id',
        'is_active',
        'reserve_price',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Fixed: Removed unnecessary parameters
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_services');
    }

    // Optional: Get only active employees for this service
    public function activeEmployees()
    {
        return $this->belongsToMany(Employee::class, 'employee_services')
                    ->wherePivot('is_active', 1)
                    ->withPivot('is_active');
    }
}
