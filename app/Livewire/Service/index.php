<?php
namespace App\Livewire\Service;

use App\Models\Service;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]

 class Index extends Component
{
    public $services ; 
    public $categories;

    public $description;
    public $cost;
    public $time;
    public $caption;
    public $category_id;
    public $branch_id;




    //

    public $edit_mode =false;
    public $current_service ;

        public $isopen = false;
    
    #[On(['branch-switched'])]
    public function refresh(){
 $branch = current_branch()->fresh(); // این لازم است

    $this->services = $branch->services()->orderBy('id', 'desc')->get();
    $this->categories = $branch->categories()->get();
    $this->branch_id = $branch->id;

     
    
    }

            public function open_modal()
    {
        
              $this->edit_mode = false;


      $this->isopen = true;

    } 
    public function mount(){
        $this->services = current_branch()->services()->get();
        $this->categories = current_branch()->categories()->get();

        $this->branch_id = current_branch()->id;

    }
    public function render()
    {
        return view("livewire.services.index");
    }

    public function store(){
        if(!$this->edit_mode){
        Service::create([
        'description' =>$this->description,
        'cost' =>$this->cost, 
        'time' =>$this->time ,
        'caption' =>$this->caption,
        'category_id'=>$this->category_id ,
        'branch_id' =>$this->branch_id,    
        ]);

 
    }else{
        $this->current_service->update([
        'description' =>$this->description,
        'cost' =>$this->cost, 
        'time' =>$this->time ,
        'caption' =>$this->caption,
        'category_id'=>$this->category_id ,
        'branch_id' =>$this->branch_id,    
        ]);

    }
    
    $branch = current_branch()->fresh(); // این لازم است

            $this->services = $branch->services()->orderBy('id', 'desc')->get();
    $this->categories = $branch->categories()->get();
    $this->branch_id = $branch->id;
                Flux::modal('services')->close();  $this->isopen = false;


        // $this->dispatch('new-service');
    }

        public function show_edit($service){
      $this->edit_mode = true ;

// dd($branch);

      $this->current_service = Service::find($service['id']);
      $this->caption= $service['caption'];
      $this->time = $service['time'];
      $this->cost = $service['cost'];
      $this->description = $service['description'];
      $this->category_id = $service['category_id'];

    

    }
    


};
