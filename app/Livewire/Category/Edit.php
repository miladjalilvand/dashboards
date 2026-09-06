<?php
namespace App\Livewire\Category;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]

 class Edit extends Component
{
    //
        public function render()
    {
        return view("livewire.categories.edit");
    }
};
