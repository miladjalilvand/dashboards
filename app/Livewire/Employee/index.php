<?php
namespace App\Livewire\Employee;

use App\Models\Employee;
use App\Models\EmployeeService;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]

 class Index extends Component
{
    //
        public $employees ;

        public $name , $caption ;

        public  $working_times =[];



        public $showModalWeekday ;
        public $endTime;
        public $startTime;
        public $weekday_selected;

        public $showModalAddService = false;

        public $services ;

        public $employeeServices ;

        public $service_ids ;
        public $employe_service_ids=[];

        public $selected_employee_id ;

        public $edit_mode = false;

        public $current_employee ;
        public $isopen = false;

        public $hours_for_select = [];
        public $minutesfor_select_numbers = [];


        public $hour_start_selected ;
        public $min_start_selected ;

        public $hour_end_selected ;
        public $min_end_selected ;



        public $farsi_week_days ;

        public function create_times_for_select(){

            $this->minutesfor_select_numbers = ['00' , 15 , 30 , 45];
            for($i = 0 ; $i < 24; $i++){
                $this->hours_for_select[] = $i;

            }
        }

        #[On('branch-switched')]
        public function refresh(){
            $branch =  current_branch()->fresh();

            $this->employees =
        $branch->employees()->get();

        $this->services = $branch->services()->get();
        $this->service_ids = $branch->services->pluck('id')->
        toArray()??[];


        }
    public function mount(){


        $this->farsi_week_days = array_values(farsi_week_days());        $this->employees =
        current_branch()->employees()->get();

        $this->services = current_branch()->services()->get();
        $this->service_ids = current_branch()->services()->pluck('id')->
        toArray()??[];
        $this->create_times_for_select();
        // dd(current_branch()->services);

    }
        public function render()
    {
        return view("livewire.employees.index");
    }
        public function open_modal()
    {
              $this->edit_mode = false;


      $this->isopen = true;

    }

    protected function rules(): array
    {
        return [


            'caption' => ['required', 'string', 'min:2', 'max:255'],
            'name' => ['required', 'string', 'min:2', 'max:255'],



        ];
    }
    protected function messages(): array
    {
        return [

            'caption.required' => 'عنوان  الزامی است.',
            'caption.min' => 'عنوان باید حداقل ۲ کاراکتر باشد.',
            'name.required' => 'نام  الزامی است.',
            'name.min' => 'نام  باید حداقل ۲ کاراکتر باشد.',


        ];
    }
    public function toggleStatus($employee_id)
    {
        $employee = Employee::findOrFail($employee_id);

        $employee->update([
            'is_active' => ! $employee->is_active,
        ]);

//        $this->refresh();
    }
    public function store(){
            $this->validate();
        $branch_id = current_branch()->id;

        if(!$this->edit_mode){

        Employee::create([
            'name' => $this->name,
            'caption' => $this->caption,
            'working_times' => json_encode($this->working_times) ,
            'branch_id' => $branch_id
        ]);}
        else {
            $this->current_employee->update([
            'name' => $this->name,
            'caption' => $this->caption,
            'working_times' => json_encode($this->working_times) ,
            'branch_id' => $branch_id
            ]);
        }

        Flux::modal('employees')->close();  $this->isopen = false;

        $this->refresh();

    }

    public function add_week_day ($week_day){


        $this->weekday_selected = $week_day;
        if(!in_array($week_day , array_keys($this->working_times))){
        $this->working_times[$week_day]=[];
        $this->showModalWeekday= true;
        }else {
        // $this->working_times = array_diff($this->working_times, [$week_day]);
        // unset($this->working_times[$week_day]);

        $this->showModalWeekday= true;

        }
    }

    public function add_time(){

        if($this->hour_end_selected  != null  &&
        $this->hour_start_selected  != null
        ){



            $this->working_times[$this->weekday_selected][]=
        ['start' =>
        [
            'h' => $this->hour_start_selected ,
            'm' => $this->min_start_selected ?? '00'
        ],


        'end' => [
            'h' => $this->hour_end_selected ,
            'm' => $this->min_end_selected  ?? '00'
        ]];
        $this->reset(['hour_end_selected','min_end_selected' , 'hour_start_selected','min_start_selected']);

        }


    }

    public function remove_time($key_item , $week_day){
      unset(  $this->working_times[$week_day][$key_item]);
    }

    public function add_service($employee_item_id){
        $this->showModalAddService =true;

        $this->employeeServices = EmployeeService::
        where('employee_id' , $employee_item_id)->get();

         $this->employe_service_ids = EmployeeService::
        where('employee_id' , $employee_item_id)->pluck('service_id')->
        toArray()??[];

        $this->selected_employee_id = $employee_item_id;

    }

    // public function add_service_to_employee($service_id){

    //     if(in_array($service_id , $this->employe_service_ids)){
    //         $this->employe_service_ids[$service_id] = null;
    //     }else {
    //         $this->employe_service_ids[] = $service_id;
    //     }
    // }
    public function add_service_to_employee($service_id)
    {
        // چک می‌کنیم آیا $service_id در آرایه وجود دارد یا نه
        $key = array_search($service_id, $this->employe_service_ids);

        if ($key !== false) {
            // اگر وجود داشت (یعنی سرویس انتخاب شده است)، آن را حذف می‌کنیم
            unset($this->employe_service_ids[$key]);
            // برای اطمینان از اینکه آرایه پیوسته بماند (اختیاری، بستگی به مصرف بعدی دارد)
            $this->employe_service_ids = array_values($this->employe_service_ids);
        } else {
            // اگر وجود نداشت، آن را اضافه می‌کنیم
            $this->employe_service_ids[] = $service_id;
        }
    }

    public function store_employe_service(){


        EmployeeService::where('employee_id' , $this->selected_employee_id)
        ->delete();
        foreach($this->employe_service_ids as $serv_id){
    EmployeeService::create([
            'employee_id' =>$this->selected_employee_id,
            'service_id'=>$serv_id
        ]);
        }



        $this->showModalAddService =false;

        $this->reset('employe_service_ids');
    }

            public function show_edit($employee){
      $this->edit_mode = true ;

// dd($branch);

      $this->current_employee = Employee::find($employee['id']);
      $this->caption= $employee['caption'];
      $this->name = $employee['name'];

$this->working_times = json_decode($employee['working_times'], true);


    }

};
