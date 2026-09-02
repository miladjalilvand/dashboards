<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panel extends Model
{
    //
    protected $table = 'panels' ;

    protected $fillable = ['id','website' , 'expired_date' , 'user_id' , 'dashboard_id','key_pass'];

    public function branches ()
    {
        return $this->hasMany(Branch::class);
    }

    public function admins ()
    {
        return $this->hasMany(Admin::class);
    }

    public function options ()
    {
        return $this->hasMany(PanelOption::class);
    }
        public function user ()
    {
        return $this->belongsTo(User::class);
    }

    public function customers (){
        return $this->hasMany(\App\Models\Customer::class);
    }

    public function panelPortofilios(){
        return $this->hasMany(PanelPortofolio::class);
    }

}
