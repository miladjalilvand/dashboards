<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class ReserveController extends Controller
{
    //
public function index(){
    $reserves = current_branch()->reserves();
}
}
