<?php
namespace App\Livewire\WebsiteLiveWire;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Reserve;
use App\Models\Service;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    /*
    |--------------------------------------------------------------------------
    | Branch
    |--------------------------------------------------------------------------
    */

    public $branches = [];

    public $branch_selected;

    public $branch_services = [];

    public $branch_categories = [];


    /*
    |--------------------------------------------------------------------------
    | State
    |--------------------------------------------------------------------------
    */

    public $state = 0;

    public $employee_service_list_selected = false;


    /*
    |--------------------------------------------------------------------------
    | Employee
    |--------------------------------------------------------------------------
    */

    public $employee_selected;

    public $selected_employee;


    /*
    |--------------------------------------------------------------------------
    | Service
    |--------------------------------------------------------------------------
    */

    public $selected_service;

    public $total_time;


    /*
    |--------------------------------------------------------------------------
    | Date
    |--------------------------------------------------------------------------
    */

    public $reserve_data;
    public $reserve_data_miladu;

    public $week_of_day;

    public $current_customer;


    /*
    |--------------------------------------------------------------------------
    | Working Time
    |--------------------------------------------------------------------------
    */

    public $working_times = [];

    public $time_of_wtimes = [];

    public $list_mins = ['00', '15', '30', '45'];


    /*
    |--------------------------------------------------------------------------
    | Time
    |--------------------------------------------------------------------------
    */

    public $selected_time;

    public $selected_end_time;

    public $select_time_modal = false;


    /*
    |--------------------------------------------------------------------------
    | Confirmation
    |--------------------------------------------------------------------------
    */

    public $confirm_reservation_modal = false;


    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    public $error_message = null;

    public $success_message = null;

    public $customers ;

    /*
    |--------------------------------------------------------------------------
    | Mount
    |--------------------------------------------------------------------------
    */

    public function mount()
    {
        $panel = Auth::user()
            ->panels()
            ->where('dashboard_id', 1)
            ->first();

        if (!$panel) {
            $this->branches = [];
            return;
        }

        $this->branches = $panel
            ->branches()
            ->get();


    }


    /*
    |--------------------------------------------------------------------------
    | Select Branch
    |--------------------------------------------------------------------------
    */

    public function onCustomerChange()
    {

    }
    public function select_branch($branchId)
    {
        $this->clearMessages();

        $branch = Branch::with([
            'services.employees',
            'categories.services.employees'
        ])->findOrFail($branchId);

        $user = Auth::user();

        $this->branch_selected = $branch;


        $this->branch_services = $branch->services;

        $this->branch_categories = $branch->categories;

        $this->state = 1;

        // Reset previous booking
        $this->resetBooking();
    }


    /*
    |--------------------------------------------------------------------------
    | Select Employee
    |--------------------------------------------------------------------------
    */

    public function select_employee(
        $employee_id,
        $total_time_service,
        $service_id
    ) {
        $panel = Auth::user()
            ->panels()
            ->where('dashboard_id', 1)
            ->first();
        $this->customers =$panel->customers;

        if($panel->customers->count()) {


            $this->current_customer = $this->customers->first()->id;

            $this->clearMessages();

            $employee = Employee::findOrFail($employee_id);

            $service = Service::findOrFail($service_id);

            $this->employee_selected = $employee->id;

            $this->selected_employee = $employee;

            $this->selected_service = $service;

            $this->total_time = (int)$total_time_service;

            $this->working_times = json_decode(
                $employee->working_times,
                true
            ) ?? [];

            // reset date/time when employee changes
            $this->reserve_data = null;

            $this->selected_time = null;

            $this->selected_end_time = null;

            $this->time_of_wtimes = [];

            $this->select_time_modal = false;

            $this->confirm_reservation_modal = false;

            $this->employee_service_list_selected = true;
        }else {
            $this->error_message = 'یک مشتری ایجاد کنید';

        }
    }


    /*
    |--------------------------------------------------------------------------
    | Save Date
    |--------------------------------------------------------------------------
    */

    public function save_date()
    {
        $this->clearMessages();

        if (!$this->employee_selected) {
            $this->error_message = 'ابتدا یک کارمند انتخاب کنید.';
            return;
        }

        if (!$this->selected_service) {
            $this->error_message = 'ابتدا یک سرویس انتخاب کنید.';
            return;
        }

        if (!$this->reserve_data) {
            $this->error_message = 'لطفاً تاریخ را انتخاب کنید.';
            return;
        }

        try {
            $selectedDate = Verta::parse($this->reserve_data);
        } catch (\Throwable $e) {
            $this->error_message = 'تاریخ وارد شده صحیح نیست.';
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Past Date
        |--------------------------------------------------------------------------
        */

        if (
            $selectedDate->format('Y-m-d') <
            Verta::today()->format('Y-m-d')
        ) {
            $this->error_message =
                'امکان انتخاب تاریخ گذشته وجود ندارد.';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Day Of Week
        |--------------------------------------------------------------------------
        */

        $this->week_of_day = (string) $selectedDate->dayOfWeek;


        $employee = Employee::findOrFail(
            $this->employee_selected
        );


        $employeeWorkingTimes = json_decode(
            $employee->working_times,
            true
        ) ?? [];


        $this->working_times =
            $employeeWorkingTimes[$this->week_of_day] ?? [];


        /*
        |--------------------------------------------------------------------------
        | Employee Is Off
        |--------------------------------------------------------------------------
        */

        if (empty($this->working_times)) {

            $this->error_message =
                'این کارمند در این روز کاری ندارد.';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Available Time Slots
        |--------------------------------------------------------------------------
        */

        $this->time_of_wtimes = [];


        foreach ($this->working_times as $workingTime) {

            $startHour =
                (int) ($workingTime['start']['h'] ?? 0);

            $startMinute =
                (int) ($workingTime['start']['m'] ?? 0);

            $endHour =
                (int) ($workingTime['end']['h'] ?? 0);

            $endMinute =
                (int) ($workingTime['end']['m'] ?? 0);


            $workingStart =
                ($startHour * 60) + $startMinute;

            $workingEnd =
                ($endHour * 60) + $endMinute;


            /*
            |--------------------------------------------------------------------------
            | Every 15 Minutes
            |--------------------------------------------------------------------------
            */

            for (
                $minute = $workingStart;
                $minute < $workingEnd;
                $minute += 15
            ) {

                $slotEnd =
                    $minute + $this->total_time;


                /*
                |--------------------------------------------------------------------------
                | Service Must Finish Before Working Time Ends
                |--------------------------------------------------------------------------
                */

                if ($slotEnd > $workingEnd) {
                    continue;
                }


                $startTime =
                    $this->minutesToTime($minute);

                $endTime =
                    $this->minutesToTime($slotEnd);


                /*
                |--------------------------------------------------------------------------
                | Check Existing Reserve
                |--------------------------------------------------------------------------
                */

                if (
                    $this->hasReservationConflict(
                        $startTime,
                        $endTime
                    )
                ) {
                    continue;
                }


                $this->time_of_wtimes[] = [
                    'start' => $startTime,
                    'end' => $endTime,
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | No Available Time
        |--------------------------------------------------------------------------
        */

        if (empty($this->time_of_wtimes)) {

            $this->error_message =
                'برای این تاریخ هیچ ساعت آزادی وجود ندارد.';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Open Time Modal
        |--------------------------------------------------------------------------
        */

        $this->select_time_modal = true;
    }


    /*
    |--------------------------------------------------------------------------
    | Select Time
    |--------------------------------------------------------------------------
    */

    public function submit_time_to_reservetion($startTime)
    {
        $this->clearMessages();

        if (!$this->selected_service) {
            $this->error_message = 'سرویس انتخاب نشده است.';
            return;
        }

        if (!$this->employee_selected) {
            $this->error_message = 'کارمند انتخاب نشده است.';
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Normalize Time
        |--------------------------------------------------------------------------
        */

        $startTime = str_pad(
            (string) $startTime,
            4,
            '0',
            STR_PAD_LEFT
        );


        $startMinutes =
            $this->timeToMinutes($startTime);


        $endMinutes =
            $startMinutes + (int) $this->total_time;


        $endTime =
            $this->minutesToTime($endMinutes);


        /*
        |--------------------------------------------------------------------------
        | Check Conflict Again
        |--------------------------------------------------------------------------
        */

        if (
            $this->hasReservationConflict(
                $startTime,
                $endTime
            )
        ) {

            $this->error_message =
                'این ساعت قبلاً رزرو شده است. لطفاً ساعت دیگری انتخاب کنید.';

            // refresh available times
            $this->select_time_modal = false;

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Save Selected Time
        |--------------------------------------------------------------------------
        */

        $this->selected_time = $startTime;

        $this->selected_end_time = $endTime;


        /*
        |--------------------------------------------------------------------------
        | Close Time Modal
        |--------------------------------------------------------------------------
        */

        $this->select_time_modal = false;


        /*
        |--------------------------------------------------------------------------
        | Open Confirmation Modal
        |--------------------------------------------------------------------------
        */

        $this->confirm_reservation_modal = true;
    }


    /*
    |--------------------------------------------------------------------------
    | Confirm Reservation
    |--------------------------------------------------------------------------
    */

    public function confirmReservation()
    {

//        $this->current_customer =$this->customers->first()->id;

        $this->clearMessages();


        /*
        |--------------------------------------------------------------------------
        | Validate Data
        |--------------------------------------------------------------------------
        */

        if (!$this->branch_selected) {
            $this->error_message = 'شعبه انتخاب نشده است.';
            return;
        }

        if (!$this->selected_service) {
            $this->error_message = 'سرویس انتخاب نشده است.';
            return;
        }
        if (!$this->current_customer) {
            $this->error_message = 'مشتزی انتخاب نشده است.';
            return;
        }

        if (!$this->selected_employee) {
            $this->error_message = 'کارمند انتخاب نشده است.';
            return;
        }

        if (!$this->reserve_data) {
            $this->error_message = 'تاریخ انتخاب نشده است.';
            return;
        }

        if (!$this->selected_time) {
            $this->error_message = 'ساعت انتخاب نشده است.';
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate End Time Again
        |--------------------------------------------------------------------------
        */

        $startMinutes =
            $this->timeToMinutes(
                $this->selected_time
            );


        $endMinutes =
            $startMinutes + (int) $this->total_time;


        $endTime =
            $this->minutesToTime($endMinutes);


        /*
        |--------------------------------------------------------------------------
        | Final Conflict Check
        |--------------------------------------------------------------------------
        */

        if (
            $this->hasReservationConflict(
                $this->selected_time,
                $endTime
            )
        ) {

            $this->confirm_reservation_modal = false;

            $this->error_message =
                'متأسفانه این ساعت همین الان توسط شخص دیگری رزرو شده است. لطفاً ساعت دیگری انتخاب کنید.';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Date
        |--------------------------------------------------------------------------
        */

        try {
            $date = Verta::parse($this->reserve_data)
                ->datetime()
                ->format('Y-m-d');

        } catch (\Throwable $e) {

            $this->error_message = 'تاریخ انتخاب شده صحیح نیست.';
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Create Reserve
        |--------------------------------------------------------------------------
        */

        Reserve::create([

            'total_time' =>
                $this->total_time,

            'discount' =>
                $this->selected_service->discount ?? 0,

            'total_cost' =>
                $this->selected_service->cost,

            'end_time' =>
                $endTime,

            'start_time' =>
                $this->selected_time,

            'customer_id' =>
                $this->current_customer??$this->customers->first()->id,

            'branch_id' =>
                $this->branch_selected->id,

            'status_id' =>
                1,

            'date' =>
                $date,

            'employee_id' =>
                $this->employee_selected,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Close Confirmation
        |--------------------------------------------------------------------------
        */

        $this->confirm_reservation_modal = false;


        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        $this->success_message =
            'نوبت شما با موفقیت ثبت شد.';


        /*
        |--------------------------------------------------------------------------
        | Reset Date & Time
        |--------------------------------------------------------------------------
        */

        $this->reserve_data = null;

        $this->selected_time = null;

        $this->selected_end_time = null;

        $this->time_of_wtimes = [];
    }


    /*
    |--------------------------------------------------------------------------
    | Reservation Conflict
    |--------------------------------------------------------------------------
    */

    private function hasReservationConflict(
        string $startTime,
        string $endTime
    ): bool {

        if (!$this->employee_selected || !$this->reserve_data) {
            return false;
        }


        try {


            $date = Verta::parse( $this->reserve_data)->datetime()->format('Y-m-d');


        } catch (\Throwable $e) {

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Overlap:
        |
        | existing_start < new_end
        | AND
        | existing_end > new_start
        |--------------------------------------------------------------------------
        */

        return Reserve::query()
            ->where('employee_id', $this->employee_selected)
            ->where('date', $date)
            ->where(function ($query) use ($startTime, $endTime) {

                $query
                    ->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            })
            ->exists();
    }


    /*
    |--------------------------------------------------------------------------
    | Time -> Minutes
    |--------------------------------------------------------------------------
    */

    private function timeToMinutes(string $time): int
    {
        $time = str_pad(
            $time,
            4,
            '0',
            STR_PAD_LEFT
        );

        $hour =
            (int) substr($time, 0, 2);

        $minute =
            (int) substr($time, 2, 2);

        return ($hour * 60) + $minute;
    }


    /*
    |--------------------------------------------------------------------------
    | Minutes -> HHMM
    |--------------------------------------------------------------------------
    */

    private function minutesToTime(int $minutes): string
    {
        $minutes %= 1440;

        $hour =
            intdiv($minutes, 60);

        $minute =
            $minutes % 60;

        return sprintf(
            '%02d%02d',
            $hour,
            $minute
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Reset Booking
    |--------------------------------------------------------------------------
    */

    private function resetBooking()
    {
        $this->employee_selected = null;

        $this->selected_employee = null;

        $this->selected_service = null;

        $this->reserve_data = null;

        $this->selected_time = null;

        $this->selected_end_time = null;

        $this->working_times = [];

        $this->time_of_wtimes = [];

        $this->select_time_modal = false;

        $this->confirm_reservation_modal = false;

        $this->employee_service_list_selected = false;
    }


    /*
    |--------------------------------------------------------------------------
    | Clear Messages
    |--------------------------------------------------------------------------
    */

    private function clearMessages()
    {
        $this->error_message = null;

        $this->success_message = null;
    }


    /*
    |--------------------------------------------------------------------------
    | Switch State
    |--------------------------------------------------------------------------
    */

    public function switchState($state)
    {
        $this->clearMessages();

        $this->state = $state;

        if ($state === 0) {
            $this->resetBooking();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view(
            'livewire.branches.index_branches'
        );
    }
}
