<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReserveEmployeeService extends Model
{
    //
    protected $table ='reserve_employee_services'; 
    protected $fillable = [ 'employee_service_id' , 'reserve_id'];
}
