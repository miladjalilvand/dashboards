<?php

namespace App\Livewire\Faqs;

use App\Models\Category;
use App\Models\Faq;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('layouts.app')]
class Create extends Component 
{
     public $categories;

     public $category_id;
    public $title;
    public $content;

    protected $rules = [
        'category_id' => 'required',
        'title'  => 'required|min:3',
        'content'  => 'required|min:5',
    ];

    public function store()
    {

        
        $this->validate();

        // Faq::create([
        //     'category_id' => $this->category_id,
        //     'title'  => $this->title,
        //     'content'  => $this->content,
        // ]);

        // Flux::toast('Your changes have been saved.');
        return redirect()->route('menu_type1');
    }

    public function mount ()
    {
        $this->categories = Category::all();
    }
    public function render()
    {
        return view("livewire.faqs.create");
    }

}