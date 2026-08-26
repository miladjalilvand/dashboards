<?php
namespace App\Livewire\SubWeb;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\CustomerUser;
use App\Models\Employee;
use App\Models\Panel;
use App\Models\Reserve;
use App\Models\Service;
use App\Models\User;
use Flux\Flux;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.blank')]
class Index extends Component
{
    /*
    |--------------------------------------------------------------------------
    | Branch
    |--------------------------------------------------------------------------
    */
    public $banners_data;
    public $aboutus;

    public $service_employees ;

    public $customer_reserves ;


    public $branches = [];

    public $branch_selected;

    public $branch_services = [];

    public $branch_categories = [];


    public $website;


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

    public $week_of_day;

    public $current_customer_id = null;
    public $user_logged_id = null;


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

    public $input_name = '';
    public $input_mobile_number = '';
    public $input_email = '';
    public $input_password = '';
    public $input_password_confirmation = '';

    public $logged = false;


    public $panel;

    // وضعیت فرم
    public $showRegisterForm = false;
    public bool $showAboutImage = false;

    public ?string $selectedAboutImage = null;


    public function openAboutImage(int $index)
    {
        if (
            !isset($this->aboutus['images']) ||
            !isset($this->aboutus['images'][$index])
        ) {
            return;
        }

        $this->selectedAboutImage =
            $this->aboutus['images'][$index];

        $this->showAboutImage = true;
    }


    public function closeAboutImage()
    {
        $this->showAboutImage = false;

        $this->selectedAboutImage = null;
    }
    public function backToMobile()
    {
        $this->showRegisterForm = false;

        $this->reset([
            'input_name',
            'input_email',
            'input_password',
            'input_password_confirmation',
        ]);

        $this->resetValidation();
    }
    public function openLoginModal()
    {
        $this->reset([
            'input_name',
            'input_mobile_number',
            'input_email',
            'input_password',
            'input_password_confirmation',
        ]);

        $this->showRegisterForm = false;

        $this->resetValidation();

        Flux::modal('customer-auth')->show();
    }

    /**
     * بررسی شماره موبایل
     */
    public function checkMobile()
    {
        $this->validate([
            'input_mobile_number' => [
                'required',
                'string',
                'regex:/^09[0-9]{9}$/',
            ],
        ], [
            'input_mobile_number.required' => 'شماره موبایل را وارد کنید.',
            'input_mobile_number.regex' => 'شماره موبایل معتبر نیست.',
        ]);

        $user = CustomerUser::where(
            'mobile',
            $this->input_mobile_number
        )->first();

        /*
        |--------------------------------------------------------------------------
        | کاربر وجود دارد
        |--------------------------------------------------------------------------
        */

        if ($user) {

            $customer = Customer::where('user_id', $user->id)
                ->where('panel_id', $this->panel->id)
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Customer برای این پنل وجود ندارد
            |--------------------------------------------------------------------------
            */

            if (!$customer) {

                $branch = $this->branches->first();

                if (!$branch) {
                    $this->addError(
                        'input_mobile_number',
                        'برای این مجموعه هیچ شعبه‌ای ثبت نشده است.'
                    );

                    return;
                }

                $customer = Customer::create([
                    'user_id' => $user->id,
//                    'branch_id' => $branch->id,
                    'panel_id' => $this->panel->id,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Login داخلی
            |--------------------------------------------------------------------------
            */

            $this->user_logged_id = $user->id;
            $this->current_customer_id = $customer->id;

            $this->logged = true;
            $this->showRegisterForm = false;

            /*
            |--------------------------------------------------------------------------
            | ذخیره در Session
            |--------------------------------------------------------------------------
            */

            session()->put('customer_id', $customer->id);

            $this->loadCustomerReserves();

            Flux::modal('customer-auth')->close();

            $this->reset([
                'input_mobile_number',
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | کاربر وجود ندارد
        |--------------------------------------------------------------------------
        */

        $this->showRegisterForm = true;
    }
    public function submitRegister()
    {
        $validated = $this->validate([
            'input_name' => [
                'required',
                'string',
                'max:255',
            ],

            'input_mobile_number' => [
                'required',
                'string',
                'regex:/^09[0-9]{9}$/',
                'unique:customer_users,mobile',
            ],
        ], [
            'input_name.required' => 'نام و نام خانوادگی را وارد کنید.',

            'input_mobile_number.required' => 'شماره موبایل را وارد کنید.',
            'input_mobile_number.regex' => 'شماره موبایل معتبر نیست.',
            'input_mobile_number.unique' => 'این شماره موبایل قبلاً ثبت شده است.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Customer User
        |--------------------------------------------------------------------------
        */

        $user = CustomerUser::create([
            'name' => $validated['input_name'],
            'mobile' => $validated['input_mobile_number'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Branch
        |--------------------------------------------------------------------------
        */

        $branch = $this->branches->first();

        if (!$branch) {
            $user->delete();

            $this->addError(
                'input_mobile_number',
                'برای این مجموعه هیچ شعبه‌ای ثبت نشده است.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Create Customer
        |--------------------------------------------------------------------------
        */

        $customer = Customer::create([
            'user_id' => $user->id,
//            'branch_id' => $branch->id,
            'panel_id' => $this->panel->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Login داخلی
        |--------------------------------------------------------------------------
        */

        $this->user_logged_id = $user->id;
        $this->current_customer_id = $customer->id;

        $this->logged = true;
        $this->showRegisterForm = false;

        $this->customer_reserves = $customer
            ->reserves()
            ->get();

        Flux::modal('customer-auth')->close();

        $this->reset([
            'input_name',
            'input_mobile_number',
        ]);

        session()->put('customer_id', $customer->id);
    }



    /**
     * خروج
     */
    public function logout(): void
    {
        $this->logged = false;

        $this->user_logged_id = null;
        $this->current_customer_id = null;

        $this->customer_reserves = [];

        $this->showRegisterForm = false;

        $this->reset([
            'input_name',
            'input_mobile_number',
        ]);

        session()->forget('customer_id');
    }
//    public function mount($website)
//    {
//
//
//        if ($this->current_customer_id) {
//            $this->logged = true;
//            $this->user_logged_id = Customer::find($this->current_customer_id)->user->id;
//            $this->customer_reserves = Customer::find($this->current_customer_id)->reserves()->get();
//        }
//        $this->website = $website;
////dd($website);
//        $panel = Panel::where('website', $this->website)->first();
//
//
//
//        $this->panel = $panel;
//        if (!$panel) {
//            $this->branches = [];
//            return;
//        }
//
//        $this->branches = $panel
//            ->branches()
//            ->get();
//
////        if (Auth::check()) {
////            $this->current_customer_id = Auth::user()->customer->id;
//
////            $customer = Customer::find($this->current_customer_id);
//
//
//
////        }
//    }

    public function mount($website)
    {
        $this->website = $website;

        $this->panel = Panel::where(
            'website',
            $this->website
        )->first();

        $this->banners_data = $this->panel->options()->where('option_id' , 1)->first()->data;
        $this->aboutus = $this->panel->options()->where('option_id' , 2)->first()->data;


        if (!$this->panel) {
            $this->branches = [];

            return;
        }

        $this->branches = $this->panel
            ->branches()->
            where('is_active' , 1)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Customer Session
        |--------------------------------------------------------------------------
        */

        $this->current_customer_id = session('customer_id');

        if ($this->current_customer_id) {

            $customer = Customer::find(
                $this->current_customer_id
            );

            if ($customer) {

                $this->logged = true;

                $this->user_logged_id = $customer->user_id;

                $this->loadCustomerReserves();

            } else {

                session()->forget('customer_id');

                $this->current_customer_id = null;
            }
        }
    }
    private function loadCustomerReserves(): void
    {
        $customerId = session('customer_id');

        if (!$customerId) {
            $this->current_customer_id = null;
            $this->customer_reserves = [];
            return;
        }

        $customer = Customer::with('reserves')->find($customerId);

        if (!$customer) {
            session()->forget('customer_id');

            $this->current_customer_id = null;
            $this->user_logged_id = null;
            $this->logged = false;
            $this->customer_reserves = [];

            return;
        }

        $this->current_customer_id = $customer->id;
        $this->user_logged_id = $customer->user_id;
        $this->logged = true;

        $this->customer_reserves = $customer->reserves;


    }
    /*
    |--------------------------------------------------------------------------
    | Select Branch
    |--------------------------------------------------------------------------
    */

    public function call_branch($branchId)
    {
        $branch = Branch::findOrFail($branchId);

        return [
            'phone' => $branch->phone,
            'mobile' => $branch->mobile,
        ];
    }

    public function select_branch($branchId)
    {
        $this->clearMessages();

        $branch = Branch::with([
            'services.employees',
            'categories.services.employees'
        ])->findOrFail($branchId);

        $this->branch_selected = $branch;
//        $this->customers =$this->branch_selected->customers ;

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

        if (!$this->logged) {
            $this->error_message = 'لطفا ابتدا ثبت نام / ورود کنید.';
            return;
        }
        $this->clearMessages();

        $employee = Employee::findOrFail($employee_id);

        $service = Service::findOrFail($service_id);

        $this->employee_selected = $employee->id;

        $this->selected_employee = $employee;

        $this->selected_service = $service;

        $this->total_time = (int) $total_time_service;

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
        if (!$this->logged) {
            $this->error_message = 'لطفا ابتدا ثبت نام / ورود کنید.';
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
//        dd([
//
//            'total_time' =>
//                $this->total_time,
//
//            'discount' =>
//                $this->selected_service->discount ?? 0,
//
//            'total_cost' =>
//                $this->selected_service->cost,
//
//            'end_time' =>
//                $endTime,
//
//            'start_time' =>
//                $this->selected_time,
//
//            'customer_id' =>
//                $this->current_customer??$this->customers->first()->id,
//
//            'branch_id' =>
//                $this->branch_selected->id,
//
//            'status_id' =>
//                1,
//
//            'date' =>
//                $date,
//
//            'employee_id' =>
//                $this->employee_selected,
//        ]);


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
                $this->current_customer_id,

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

        $this->state= 0 ;


        $customer = Customer::find(
            $this->current_customer_id
        );

        if ($customer) {

            $this->logged = true;

            $this->user_logged_id = $customer->user_id;

            $this->loadCustomerReserves();

        } else {

            session()->forget('customer_id');

            $this->current_customer_id = null;
        }
        $this->resetBooking() ;

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
            $date = Verta::parse(
                $this->reserve_data
            )->format('Y-m-d');

        } catch (\Throwable $e) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Overlap
        |--------------------------------------------------------------------------
        |
        | existing_start < new_end
        | AND
        | existing_end > new_start
        |
        */
        $date = Verta::parse($this->reserve_data)
            ->datetime()
            ->format('Y-m-d');
        return Reserve::query()
            ->where('employee_id', $this->employee_selected)
            ->where('date', $date)
            ->where('start_time', '<=', $endTime)
            ->where('end_time', '>=', $startTime)
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
            'livewire.subweb.index_branches'
        );
    }
}
