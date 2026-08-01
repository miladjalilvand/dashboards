<?php

namespace App\Livewire\Category;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]

class Create extends Component
{
    //
    public function render()
    {
        return view("livewire.categories.create");
    }
};