<?php

namespace App\Livewire\Dashboard;

use App\Models\Dashboard;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $dashboards_list;

    public $panels_user;

    public function mount()
    {

        $this->dashboards_list = Dashboard::all();

        $user = Auth::user();

        $this->panels_user = Auth::user()->panels()->get();

    }

    public function render()
    {
        return view('livewire.dashboard.index')->layout('layouts.blank');
    }

    public function is_paid($dashboard_id)
    {
        return $this->panels_user
            ->where('dashboard_id', $dashboard_id)
            ->isNotEmpty();
    }
}
