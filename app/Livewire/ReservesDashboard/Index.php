<?php
namespace App\Livewire\ReservesDashboard;

use App\Models\Reserve;
use Flux\Flux;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

use App\Models\Branch;
use Illuminate\Support\Carbon;

#[Layout('layouts.app')]

 class Index extends Component
{

    public $reserves ;
    public $branches;

    public $current_branch;
    public $current_branch_id;
    public $selected_date;

    public $selected_days =[];
    public $selectedReserve = null;

    public function showReserve($id)
    {
        $this->selectedReserve = Reserve::with([
            'branch',
            'employee',
            'customer',
            'status',
        ])->findOrFail($id);

        Flux::modal('reserve-details')->show();
    }

    public function markReviewed()
    {
        $this->selectedReserve->update([
            'status_id' => 2,
        ]);

        $this->selectedReserve->refresh();
//
//        $this->reserves = Reserve::with([
//            'branch',
//            'employee',
//            'customer',
//            'status',
//        ])->get();

        Flux::toast(
            heading: 'موفق',
            text: 'وضعیت رزرو بروزرسانی شد.',
            variant: 'success'
        );
    }
    public function change_list_days($type){
        switch($type){
            case 'next7' :
                foreach($this->selected_days as $index => $date){
                   $this->selected_days[$index] = $date->addDays(7);
                };
                break;

            case 'past7' :
                foreach($this->selected_days as $index => $date){
                   $this->selected_days[$index] = $date->addDays(-7);
                };
                break;

        }
    }

    public function show_days ($startdate=null) {

        if($startdate == null){
            $startdate = now()->addDays(-1);

        }

        $list_days=[];

        for($i = 8 ; $i > 0 ;  $i--){
            $list_days[]=$startdate->addDays($i);
        }

         $this->selected_days = $list_days;


    }

    public function select_date ($date){

        $this->selected_date = $date;

        $this->reserves = $this->current_branch->reserves()->where('date' , $this->selected_date)->get();


    }


    public function change_status(){

    }

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
    public function mount(){
        // dd(current_branch());
        $this->show_days();




        $this->branches = Branch::all();
        $this->current_branch_id = current_branch()->id;
        $this->current_branch = current_branch();
//        dd($this->selected_days);
        $this->select_date(now()->format('Y-m-d'));

//         $this->reserves = current_branch()->reserves();

    }
    public function render()
    {
        return view("livewire.reserves_dashboard.index");
    }

    //not works
//     public function updatedCurrentBranchId()
// {
//     dd('updated');
// }

//works
    public function onBranchChange()
    {
        $user = Auth::user();



        if($user->role_id == 1){

            $this->current_branch_id = $this->current_branch_id;
            $this->current_branch = Branch::find($this->current_branch_id);
        }else {

        }


        $this->reserves = $this->current_branch->reserves()->where('date' , verta($this->selected_date)->format('Y-m-d'))->get();


    }
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
