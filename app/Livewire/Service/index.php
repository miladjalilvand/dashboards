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
    public $reserve_price;




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
        if(!$this->edit_mode){
         $this->reset(['description','cost','time','caption','category_id']);

        }

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

    public function toggleStatus($service_id)
    {
        $service = Service::findOrFail($service_id);

        $service->update([
            'is_active' => ! $service->is_active,
        ]);

//        $this->refresh();
    }

    public function store()
    {
        $this->validate();

        if (!$this->edit_mode) {
            Service::create([
                'description' => $this->description,
                'cost' => filled($this->cost) ? $this->cost : 0,
                'time' => filled($this->time) ? $this->time : 0,
                'caption' => $this->caption,
                'category_id' => $this->category_id,
                'branch_id' => $this->branch_id,
                'reserve_price' => $this->reserve_price,
            ]);
        } else {
            $this->current_service->update([
                'description' => $this->description,
                'cost' => filled($this->cost) ? $this->cost : 0,
                'time' => filled($this->time) ? $this->time : 0,
                'caption' => $this->caption,
                'category_id' => $this->category_id,
                'branch_id' => $this->branch_id,
                'reserve_price' => $this->reserve_price,
            ]);
        }

        $branch = current_branch()->fresh();

        $this->services = $branch->services()
            ->orderBy('id', 'desc')
            ->get();

        $this->categories = $branch->categories()->get();
        $this->branch_id = $branch->id;

        Flux::modal('services')->close();
        $this->isopen = false;
    }
//
//    public function store(){
//        $this->validate();
//        if(!$this->edit_mode){
//            Service::create([
//                'description' =>$this->description,
//                'cost' => filled($this->cost) ? $this->cost : 0,
//                'time' => filled($this->time) ? $this->time : 0,
//                'caption' =>$this->caption,
//                'category_id'=>$this->category_id ,
//                'branch_id' =>$this->branch_id,
//                'reserve_price' =>$this->reserve_price,
//            ]);
//
//
//        }else{
//            $this->current_service->update([
//                'description' =>$this->description,
//                'cost' => filled($this->cost) ? $this->cost : 0,
//                'time' => filled($this->time) ? $this->time : 0,
//                'caption' =>$this->caption,
//                'category_id'=>$this->category_id ,
//                'branch_id' =>$this->branch_id,
//                'reserve_price' =>$this->reserve_price,
//
//            ]);
//
//        }
//
//        $branch = current_branch()->fresh(); // این لازم است
//
//        $this->services = $branch->services()->orderBy('id', 'desc')->get();
//        $this->categories = $branch->categories()->get();
//        $this->branch_id = $branch->id;
//        Flux::modal('services')->close();  $this->isopen = false;
//
//
//
//        // $this->dispatch('new-service');
//    }
    protected function rules(): array
    {
        return [
            'caption' => ['required', 'string', 'max:255'],
            'time' => ['required', 'integer', 'min:1'],
            'cost' => ['nullable', 'integer', 'min:0'],
            'reserve_price' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'branch_id' => ['required', 'exists:branches,id'],
        ];
    }

    protected function messages(): array
    {
        return [
            'caption.required' => 'وارد کردن عنوان الزامی است.',
            'caption.string' => 'عنوان باید به صورت متن باشد.',
            'caption.max' => 'عنوان نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

            'time.required' => 'وارد کردن زمان الزامی است.',
            'time.integer' => 'زمان باید به صورت عدد باشد.',
            'time.min' => 'زمان باید حداقل ۱ باشد.',

            'cost.integer' => 'مبلغ باید به صورت عدد باشد.',
            'cost.min' => 'مبلغ نمی‌تواند منفی باشد.',

            'reserve_price.integer' => 'مبلغ ثبت نوبت باید به صورت عدد باشد.',
            'reserve_price.min' => 'مبلغ ثبت نوبت نمی‌تواند منفی باشد.',

            'description.string' => 'توضیحات باید به صورت متن باشد.',

            'category_id.required' => 'انتخاب دسته‌بندی الزامی است.',
            'category_id.exists' => 'دسته‌بندی انتخاب شده معتبر نیست.',

            'branch_id.required' => 'انتخاب شعبه الزامی است.',
            'branch_id.exists' => 'شعبه انتخاب شده معتبر نیست.',
        ];
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
