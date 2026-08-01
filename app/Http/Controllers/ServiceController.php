<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    //



      public function create(){

        return view('pages.service.index');
        
    }

    public function store(Request $request){

        Service::create($request->all());
        return view('pages.service.index');
        
    }


    public function edit(Service $service){
        return view('pages.service.edit', compact('service'));
    }


    public function update(Request $request ,Service $service){

        // $validated = $request->validate([
          
        // ]);

        $service->update($request->all());
        
        return view('pages.service.index');
        
    }
}
