<?php

namespace App\Livewire\Reserves;

use App\Models\Branch;
use App\Models\Reserve;
use Carbon\Carbon;
use Flux\Flux;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public $reserves = [];
    public $branches = [];

    public $current_branch;
    public $current_branch_id;

    public $selected_date;
    public $selected_days = [];

    public $selectedReserve = null;

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    public $reserve_filter = 'all';

    public $reserve_sort = 'newest';


    /*
    |--------------------------------------------------------------------------
    | Load Reserves
    |--------------------------------------------------------------------------
    */

    public function loadReserves()
    {
        if (!$this->current_branch_id) {
            $this->reserves = collect();

            return;
        }

        $query = Reserve::query()
            ->with([
                'branch',
                'employee',
                'customer',
                'status',
            ])
            ->where('branch_id', $this->current_branch_id);


        /*
        |--------------------------------------------------------------------------
        | Filter
        |--------------------------------------------------------------------------
        */

        switch ($this->reserve_filter) {

            case 'past':

                $today = Verta::now()->format('Y-m-d');

                $query->whereDate('date', '<', $today);

                break;


            case 'future':

                $today = Verta::now()->format('Y-m-d');

                $query->whereDate('date', '>=', $today);

                break;


            case 'approved':

                $query->where('status_id', 2);

                break;


            case 'unapproved':

                $query->where('status_id', '!=', 2);

                break;


            case 'pending':

                $query->where('status_id', 1);

                break;


            case 'all':
            default:

                // بدون فیلتر

                break;
        }


        /*
        |--------------------------------------------------------------------------
        | Sort
        |--------------------------------------------------------------------------
        */

        if ($this->reserve_sort === 'oldest') {

            $query
                ->orderBy('date', 'asc')
                ->orderBy('start_time', 'asc');

        } else {

            $query
                ->orderBy('date', 'desc')
                ->orderBy('start_time', 'desc');
        }


        /*
        |--------------------------------------------------------------------------
        | Execute Query
        |--------------------------------------------------------------------------
        */

        $this->reserves = $query->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Filter Changed
    |--------------------------------------------------------------------------
    */

    public function updatedReserveFilter()
    {
        $this->loadReserves();
    }


    /*
    |--------------------------------------------------------------------------
    | Sort Changed
    |--------------------------------------------------------------------------
    */

    public function updatedReserveSort()
    {
        $this->loadReserves();
    }


    /*
    |--------------------------------------------------------------------------
    | Show Reserve
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Mark Reviewed
    |--------------------------------------------------------------------------
    */

    public function markReviewed()
    {
        if (!$this->selectedReserve) {
            return;
        }

        $this->selectedReserve->update([
            'status_id' => 2,
        ]);

        $this->selectedReserve->refresh();

        /*
        | جدول را با فیلتر فعلی دوباره بارگذاری کن
        */

        $this->loadReserves();

        Flux::toast(
            heading: 'موفق',
            text: 'وضعیت رزرو بروزرسانی شد.',
            variant: 'success'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Change Days
    |--------------------------------------------------------------------------
    */

    public function change_list_days($type)
    {
        switch ($type) {

            case 'next7':

                foreach ($this->selected_days as $index => $date) {
                    $this->selected_days[$index] = $date->copy()->addDays(7);
                }

                break;


            case 'past7':

                foreach ($this->selected_days as $index => $date) {
                    $this->selected_days[$index] = $date->copy()->subDays(7);
                }

                break;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Show Days
    |--------------------------------------------------------------------------
    */

    public function show_days($startdate = null)
    {
        if ($startdate === null) {
            $startdate = now();
        }

        $list_days = [];

        for ($i = 7; $i > 0; $i--) {

            $list_days[] = $startdate->copy()->addDays($i);
        }

        $this->selected_days = $list_days;
    }


    /*
    |--------------------------------------------------------------------------
    | Select Date
    |--------------------------------------------------------------------------
    */

    public function select_date($date)
    {
        $this->selected_date = $date;
    }


    /*
    |--------------------------------------------------------------------------
    | Mount
    |--------------------------------------------------------------------------
    */

    public function mount()
    {
        /*
        |--------------------------------------------------------------------------
        | Days
        |--------------------------------------------------------------------------
        */

        $this->show_days();

        $this->select_date(
            $this->selected_days[6]
        );


        /*
        |--------------------------------------------------------------------------
        | User / Branches
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();


        if ($user->role_id == 1) {

            $panel = $user->panels()
                ->where('dashboard_id', 1)
                ->first();

            if ($panel) {

                $this->branches = $panel
                    ->branches()
                    ->get();

            } else {

                $this->branches = collect();

            }


            /*
            |--------------------------------------------------------------------------
            | First Branch
            |--------------------------------------------------------------------------
            */

            $this->current_branch = $this->branches->first();

            $this->current_branch_id =
                $this->current_branch?->id;


        } else {

            $this->branches = collect();

        }


        /*
        |--------------------------------------------------------------------------
        | Load Initial Reserves
        |--------------------------------------------------------------------------
        */

        $this->loadReserves();
    }


    /*
    |--------------------------------------------------------------------------
    | Branch Changed
    |--------------------------------------------------------------------------
    */

    public function onBranchChange()
    {
        if (!$this->current_branch_id) {
            $this->reserves = collect();

            return;
        }


        $this->current_branch = Branch::find(
            $this->current_branch_id
        );


        $this->loadReserves();
    }


    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view('livewire.reserves.index');
    }
}

