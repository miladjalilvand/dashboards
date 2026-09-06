<?php

namespace App\Livewire\Faqs;

use App\Models\Faq;
use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('layouts.app')]
class Index extends Component 
{
    public $faqs;

    public function mount()
    {
        $this->faqs = Faq::all();
    }
    public function render()
    {
        return view("livewire.faqs.index");
    }

}