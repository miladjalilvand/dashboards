<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeService extends Model
{
    //
    protected $table ='employee_services'; 
    protected $fillable = [ 'employee_id' , 'service_id' , 'is_active'];

   public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
