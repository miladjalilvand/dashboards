<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //


    public function index ()
    {
        $categories = current_branch()->categories();

    }
    
    public function create(){

        return view('pages.category.index');
        
    }

    public function store(Request $request){

        Category::create($request->all());
        return view('pages.category.index');
        
    }


    public function edit(Category $category){
        return view('pages.category.edit', compact('category'));
    }


    public function update(Request $request ,Category $category){

        // $validated = $request->validate([
          
        // ]);

        $category->update($request->all());
        
        return view('pages.category.index');
        
    }
}
