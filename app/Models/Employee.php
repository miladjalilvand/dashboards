<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';
    protected $fillable = [
        'working_times',
        'caption', 
        'name', 
        'branch_id', 
        'is_active',
        'employee_id'
    ];

    // Fixed: Use snake_case for method name (convention)
    public function employeeServices()
    {
        return $this->hasMany(EmployeeService::class);
    }

    // Fixed: Removed unnecessary parameters (Laravel auto-detects)
    public function services()
    {
        return $this->belongsToMany(Service::class, 'employee_services')
                    ->withPivot('is_active')  // Include pivot column
                    ->withTimestamps();        // If you have timestamps in pivot
    }
    
    // Optional: Get only active services
    public function activeServices()
    {
        return $this->belongsToMany(Service::class, 'employee_services')
                    ->wherePivot('is_active', true)
                    ->withPivot('is_active');
    }
}