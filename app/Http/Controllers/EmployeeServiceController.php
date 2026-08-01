<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\EmployeeService;
use Illuminate\Http\Request;

class EmployeeServiceController extends Controller
{
    //


    public function create(){

        return view('pages.employeeService.index');
        
    }

    public function store(Request $request){

        EmployeeService::create($request->all());
        return view('pages.employeeService.index');
        
    }


    public function edit(EmployeeService $EmployeeService){
        return view('pages.employeeService.edit', compact('EmployeeService'));
    }


    public function update(Request $request ,EmployeeService $EmployeeService){

        // $validated = $request->validate([
          
        // ]);

        $EmployeeService->update($request->all());
        
        return view('pages.employeeService.index');
        
    }
}
