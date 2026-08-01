<?php
namespace App\Livewire\Branch;

use App\Models\Admin;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]

 class Index extends Component
{
    //
    public $branches ;

    public $showModal ;

    public $caption ,$phone , $mobile , $address , $location , $working_times ;

    public $edit_mode =false;


    public $current_branch ;




    // #[On('branch-created')]
    public function refresh(){
        //   dd('refresh called');

          $user = Auth::user();
        $this->branches = $user->admin->panel->branches;
    }


    public function mount()
    {

        $user = Auth::user();
        $this->branches = $user->admin->panel->branches;


    }



    public function render()
    {
        return view("livewire.branches.index");
    }

    public function reload_need_func(){
      if( in_array($this->branches->count() ,[0 , 1 ])){
          return redirect()->route('dashboards');


    }
  }

    public function store()
    {
        $user = Auth::user();

       if(!$this->edit_mode){
               Branch::create([
            'caption'=> $this->caption ,
           'phone'=> $this->phone ,
            'mobile'=>$this->mobile ,
           'location'=> $this->location ,
           'address'=> $this->address ,
          'working_times'=>  $this->working_times,
          'panel_id' => panelID($user)
        ]);


                // $this->dispatch('branch-created');
        // $this->reset();


        // $this->dispatch('delete-user-button');

      }else{
               $this->current_branch->update([
            'caption'=> $this->caption ,
           'phone'=> $this->phone ,
            'mobile'=>$this->mobile ,
           'location'=> $this->location ,
           'address'=> $this->address ,
          'working_times'=>  $this->working_times,
          'panel_id' => panelID($user)
        ]);
      }
            $user = Auth::user();
        $this->branches = $user->admin->panel->branches;
       $this->reload_need_func();
        $this->showModal = false ;
    }

    public function show_edit($branch){
      $this->edit_mode = true ;
      $this->showModal = true;
// dd($branch);

$this->current_branch =Branch::find($branch['id']);

      $this->caption= $branch['caption'];
      $this->address = $branch['address'];
      $this->phone = $branch['phone'];
      $this->mobile = $branch['mobile'];
      $this->location = $branch['location'];
      $this->working_times = $branch['working_times'];



    }

};
