<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanelPortofolio extends Model
{
    //
    protected $table = 'panel_portofolios';
    protected $fillable = ['panel_id' , 'service_id' , 'employee_id' , 'caption','image' , 'branch_id'];
    public function employee(){
        return $this->belongsTo(Employee::class);

    }
    public function service(){

        return $this->belongsTo(Service::class);

    }
    public function branch(){
        return $this->belongsTo(Branch::class);

    }
}
