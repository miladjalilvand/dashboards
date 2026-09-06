<?php

namespace App\Livewire\Elementor\PanelPortofolios;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\PanelPortofolio;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithFileUploads;

    public $portfolios = [];

    public $services = [];

    public $employees = [];

    public $branches = [];

    public bool $showDialog = false;

    public ?int $editingId = null;

    public $service_id = '';

    public $employee_id = '';

    public string $caption = '';

    public $image = null;

    public $selected_branch = '';


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
            ->firstOrFail();

        $this->branches = $panel->branches;

        $this->loadData($panel->id);
    }


    /*
    |--------------------------------------------------------------------------
    | Load Portfolios
    |--------------------------------------------------------------------------
    */

    private function loadData($panelId)
    {
        $this->portfolios = PanelPortofolio::query()
            ->where('panel_id', $panelId)
            ->with([
                'service',
                'employee',
                'branch',
            ])
            ->latest()
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Branch Changed
    |--------------------------------------------------------------------------
    */

    public function updatedSelectedBranch($value)
    {
        // ریست انتخاب‌های قبلی
        $this->service_id = '';
        $this->employee_id = '';

        $this->services = [];
        $this->employees = [];

        if (!$value) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Services
        |--------------------------------------------------------------------------
        */

        $this->services = Service::query()
            ->where('branch_id', $value)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Employees
        |--------------------------------------------------------------------------
        */

        $this->employees = Employee::query()
            ->where('branch_id', $value)
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Open Dialog
    |--------------------------------------------------------------------------
    */

    public function openDialog(?int $id = null)
    {
        $this->resetValidation();

        $this->editingId = $id;

        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        if ($id === null) {

            $this->selected_branch = '';

            $this->service_id = '';

            $this->employee_id = '';

            $this->caption = '';

            $this->image = null;

            $this->services = [];

            $this->employees = [];

            $this->showDialog = true;

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Edit
        |--------------------------------------------------------------------------
        */

        $panel = Auth::user()
            ->panels()
            ->where('dashboard_id', 1)
            ->firstOrFail();

        $portfolio = PanelPortofolio::query()
            ->where('panel_id', $panel->id)
            ->findOrFail($id);


        $this->selected_branch = $portfolio->branch_id;

        $this->service_id = $portfolio->service_id;

        $this->employee_id = $portfolio->employee_id;

        $this->caption = $portfolio->caption ?? '';

        $this->image = null;


        /*
        |--------------------------------------------------------------------------
        | Load Branch Data
        |--------------------------------------------------------------------------
        */

        $this->services = Service::query()
            ->where('branch_id', $this->selected_branch)
            ->get();

        $this->employees = Employee::query()
            ->where('branch_id', $this->selected_branch)
            ->get();


        $this->showDialog = true;
    }


    /*
    |--------------------------------------------------------------------------
    | Close Dialog
    |--------------------------------------------------------------------------
    */

    public function closeDialog()
    {
        $this->showDialog = false;

        $this->reset([
            'editingId',
            'selected_branch',
            'service_id',
            'employee_id',
            'caption',
            'image',
        ]);

        $this->services = [];

        $this->employees = [];

        $this->resetValidation();
    }


    /*
    |--------------------------------------------------------------------------
    | Save
    |--------------------------------------------------------------------------
    */

    public function save()
    {
        $this->validate([
            'selected_branch' => [
                'required',
                'exists:branches,id',
            ],

            'service_id' => [
                'required',
                'exists:services,id',
            ],

            'employee_id' => [
                'required',
                'exists:employees,id',
            ],

            'caption' => [
                'required',
                'string',
                'max:2000',
            ],

            'image' => [
                $this->editingId === null
                    ? 'required'
                    : 'nullable',

                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);


        $panel = Auth::user()
            ->panels()
            ->where('dashboard_id', 1)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        if ($this->editingId) {

            $portfolio = PanelPortofolio::query()
                ->where('panel_id', $panel->id)
                ->findOrFail($this->editingId);


            /*
            | New Image
            */

            if ($this->image) {

                if (
                    $portfolio->image &&
                    str_starts_with($portfolio->image, 'portfolios/')
                ) {
                    Storage::disk('public')
                        ->delete($portfolio->image);
                }


                $portfolio->image = $this->image->store(
                    'portfolios',
                    'public'
                );
            }


            $portfolio->branch_id = $this->selected_branch;

            $portfolio->service_id = $this->service_id;

            $portfolio->employee_id = $this->employee_id;

            $portfolio->caption = $this->caption;

            $portfolio->save();
        }


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        else {

            $imagePath = $this->image->store(
                'portfolios',
                'public'
            );


            PanelPortofolio::create([

                'panel_id' => $panel->id,

                'branch_id' => $this->selected_branch,

                'service_id' => $this->service_id,

                'employee_id' => $this->employee_id,

                'caption' => $this->caption,

                'image' => $imagePath,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Reload
        |--------------------------------------------------------------------------
        */

        $this->loadData($panel->id);

        $this->closeDialog();
    }


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function delete(int $id)
    {
        $panel = Auth::user()
            ->panels()
            ->where('dashboard_id', 1)
            ->firstOrFail();


        $portfolio = PanelPortofolio::query()
            ->where('panel_id', $panel->id)
            ->findOrFail($id);


        if (
            $portfolio->image &&
            str_starts_with($portfolio->image, 'portfolios/')
        ) {
            Storage::disk('public')
                ->delete($portfolio->image);
        }


        $portfolio->delete();


        $this->loadData($panel->id);
    }


    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view(
            'livewire.elementor.panel_portofolios'
        );
    }
}
