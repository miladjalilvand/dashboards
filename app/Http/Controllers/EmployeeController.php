<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    //

    public function index ()
    {
        $employees = current_branch()->employees();

    }
      public function create(){

        return view('pages.employee.index');
        
    }

    public function store(Request $request){

        Employee::create($request->all());
        return view('pages.employee.index');
        
    }


    public function edit(Employee $employee){
        return view('pages.employee.edit', compact('employee'));
    }


    public function update(Request $request ,Employee $employee){

        // $validated = $request->validate([
          
        // ]);

        $employee->update($request->all());
        
        return view('pages.employee.index');
        
    }
}
