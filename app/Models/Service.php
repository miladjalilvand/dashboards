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
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Fixed: Removed unnecessary parameters
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_services')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }
    
    // Optional: Get only active employees for this service
    public function activeEmployees()
    {
        return $this->belongsToMany(Employee::class, 'employee_services')
                    ->wherePivot('is_active', true)
                    ->withPivot('is_active');
    }
}