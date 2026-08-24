<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanelOption extends Model
{
    //
    protected $table ='panel_options';

    protected $fillable = ['option_id' , 'panel_id'];

    public function option (){
        return $this->belongsTo(Option::class);

    }
}
