<?php

namespace App\Livewire\Branch;

use App\Models\Branch;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]

class Create extends Component
{
    //
    public $caption ,$phone , $mobile , $address , $location , $working_times ;

    public $can_back;

        protected $rules = [
        'caption' => 'required',
        // 'phone'  => 'required|min:3',
        'mobile'  => 'required|min:5',
        'address' => 'required',
        // 'location'  => 'required|min:3',
        'working_times'  => 'required|min:5',
    ];

    // public function mount(){
    //     $this->can_back = false;
    // }

    public function render()
    {
        return view("livewire.branches.create");
    }

    public function store()
    {

        // $this->validate();
    //    $user = userAUTH();

        // Branch::create([
        //     'caption'=> $this->caption , 
        //    'phone'=> $this->phone , 
        //     'mobile'=>$this->mobile , 
        //    'location'=> $this->location , 
        //    'address'=> $this->address ,
        //   'working_times'=>  $this->working_times, 
        //   'panel_id' => panelID($user)
        // ]);

        $this->can_back =  !$this->can_back;
        // dd($this->can_back);

        // $this->reset();

        //  $this->emit('branch-created');
//باید وقتی در یک مسیر عستند این اعمال شود
// $this->dispatch('branch-created');

// return Redirect::route('branches.index');

    }

    
};