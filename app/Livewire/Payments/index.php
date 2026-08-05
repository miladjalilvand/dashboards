<?php
namespace App\Livewire\Payments;

use App\Models\Branch;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]

 class Index extends Component
{
    public $branches;
    public $branch_selected ;
    public $current_branch_id;

    public $branch_payments ;

    public function mount(){
        $this->branches = Auth::user()->panels()->where('dashboard_id' , 1)->first()->branches;
        $this->branch_selected = $this->branches->first();
//
        $this->branch_payments =$this->branch_selected->payments;

    }

    public function render(){
        return view("livewire.payments.index");
    }




    public function onBranchChange()
{
$this->branch_selected = Branch::find($this->current_branch_id);
    $this->branch_payments =$this->branch_selected->payments;

dd($this->current_branch_id);
}


//     public $services ;
//     public $categories;

//     public $description;
//     public $cost;
//     public $time;
//     public $caption;
//     public $category_id;
//     public $branch_id;


//     public $showModal ;

//     //

//     public $edit_mode =false;
//     public $current_service ;

//     #[On(['branch-switched'])]
//     public function refresh(){
//  $branch = current_branch()->fresh(); // این لازم است

//     $this->services = $branch->services()->orderBy('id', 'desc')->get();
//     $this->categories = $branch->categories()->get();
//     $this->branch_id = $branch->id;



//     }
//     public function mount(){
//         $this->services = current_branch()->services;
//         $this->categories = current_branch()->categories;
//         $this->branch_id = current_branch()->id;

//     }
//     public function render()
//     {
//         return view("livewire.services.index");
//     }

//     public function store(){
//         if(!$this->edit_mode){
//         Service::create([
//         'description' =>$this->description,
//         'cost' =>$this->cost,
//         'time' =>$this->time ,
//         'caption' =>$this->caption,
//         'category_id'=>$this->category_id ,
//         'branch_id' =>$this->branch_id,
//         ]);


//     }else{
//         $this->current_service->update([
//         'description' =>$this->description,
//         'cost' =>$this->cost,
//         'time' =>$this->time ,
//         'caption' =>$this->caption,
//         'category_id'=>$this->category_id ,
//         'branch_id' =>$this->branch_id,
//         ]);

//     }

//     $branch = current_branch()->fresh(); // این لازم است

//             $this->services = $branch->services()->orderBy('id', 'desc')->get();
//     $this->categories = $branch->categories()->get();
//     $this->branch_id = $branch->id;
//         $this->showModal = false ;

//         // $this->dispatch('new-service');
//     }

//         public function show_edit($service){
//       $this->edit_mode = true ;
//       $this->showModal = true;
// // dd($branch);

//       $this->current_service = Service::find($service['id']);
//       $this->caption= $service['caption'];
//       $this->time = $service['time'];
//       $this->cost = $service['cost'];
//       $this->description = $service['description'];
//       $this->category_id = $service['category_id'];



//     }



};
