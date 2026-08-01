<?php
namespace App\Livewire\Service;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]

 class Edit extends Component
{
    //
        public function render()
    {
        return view("livewire.services.edit");
    }
};
