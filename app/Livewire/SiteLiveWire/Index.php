<?php

namespace App\Livewire\SiteLiveWire;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Reserve;
use App\Models\Service;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;


class Index extends Component
{

    public $panel ;
    public $branches = [];

    public $branch_selected;

    public $branch_services = [];

    public $branch_categories = [];

    public $employee_service_list_selected = false;

    public $state = 0;

    public $reserve_data;

    public $employee_selected;

    public $select_time_modal = false;

    public $working_times;

    public $week_of_day;

    public $time_of_wtimes;

    public $list_mins = ['00', 15, 30, 45];

    public $total_time;

    public $selected_service ;

    public function select_employee($employee_id , $total_time_service , $service_id)
    {

        $this->total_time =(int) $total_time_service;
        $this->employee_selected = $employee_id;
        $employee = Employee::find($employee_id);

        $this->working_times = json_decode($employee->working_times, true);

        $this->selected_service = Service::find($service_id);


    }

    public function save_date()
    {
        if(Verta::parse($this->reserve_data)->format('y-m-d') >= Verta::today()->format('y-m-d')) {
            $this->time_of_wtimes = [];
            $this->week_of_day = (string)Verta::parse($this->reserve_data)->dayOfWeek;
            $employee = Employee::find($this->employee_selected);

            $this->working_times = json_decode($employee->working_times, true)[$this->week_of_day] ?? [];
            $list_1 = [];


            foreach ($this->working_times as $working_itens_item) {
                $sh = (int)$working_itens_item['start']['h'];
                $eh = (int)$working_itens_item['end']['h'];

                $list_created = [];
                for ($ind = $sh; $sh < $eh; $sh++) {
                    $list_created[] = $sh;
                }
                $list_1 = array_merge($list_1, $list_created); // ← FIX: assign the result

            }

            foreach ($list_1 as $list_item) {

                $this->time_of_wtimes[] = $list_item;

            }


            $this->select_time_modal = true;
        }
        else {
            dd('is passed');
        }

    }

    public function submit_time_to_reservetion($input_time)
    {


        $employee = Employee::find($this->employee_selected);

        $input_time_num = (string) $input_time; // مثال: 2300
        $total_time = $this->total_time; // مثال: 80

        $hour = (int) substr($input_time_num, 0, 2);
        $minute = (int) substr($input_time_num, 2, 2);

        // تبدیل به دقیقه
        $totalMinutes = ($hour * 60) + $minute;

        // جمع
        $totalMinutes += $total_time;

        // عبور از 24 ساعت
        $totalMinutes %= 1440;

        // تبدیل مجدد
        $endHour = floor($totalMinutes / 60);
        $endMinute = $totalMinutes % 60;

        // خروجی HHMM
        $end_time = sprintf('%02d%02d', $endHour, $endMinute);


        $reserves_start_check = Reserve::whereBetween('start_time', [$input_time_num ,$endHour.$endMinute ])->
        orWhereBetween('end_time' ,[$input_time_num ,$endHour.$endMinute ])->
        where('employee_id' ,$this->employee_selected  )->get();

        if($reserves_start_check->count() > 0) {
             //بررسی شود کع پابان هم ک.چیکتر باشد

            dd('is alreay has a reserve');
        }else{
          Reserve::create([
              'total_time' => $total_time ,
              'discount' => $this->selected_service->discount ,
              'total_cost' => $this->selected_service->cost,
              'end_time' =>$end_time,
              'start_time'  => $input_time,
              'customer_id' => Auth::user()->id ,
              'branch_id' => $this->branch_selected->id,
              'status_id'=>1,//در انتظار بررسی
              'date' => Verta::parse($this->reserve_data),
              'employee_id' => $this->employee_selected,
          ]);
        }
    }

    public function mount()
    {

        dd($this->panel??66);
        $this->branches = Auth::user()->panels()->where('dashboard_id' , 1)->first()->
        branches()
            ->get();
    }

    public function render()
    {
        return view('livewire.branches.index_branches');
    }

    public function switchState($state)
    {
        $this->state = $state;
    }

    public function select_branch($branchId)
    {
        $branch = Branch::with(['services.employees', 'categories.services'])
            ->findOrFail($branchId);

        $this->branch_selected = $branch;

        $this->branch_services = $branch->services;
        $this->branch_categories = $branch->categories;

        $this->state = 1;
    }

    public function select_to_new_reserve()
    {
        // بعداً
    }
}
