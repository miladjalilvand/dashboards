<?php

namespace App\Livewire\Charts\PanelCharts;

use App\Models\Branch;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public $totalReservations = 0;
    public $totalIncome = 0;
    public $totalDis = 0;
    public $totalCustomers = 0;

    public array $chartData = [
        'labels' => [],
        'values' => [],
    ];

    public $branches = [];
    public $current_branch_id = null;

    public $branch_reserves = [];

    public function mount()
    {
        $this->loading_data();
    }

    public function loading_data()
    {
        $panel = Auth::user()
            ->panels()
            ->where('dashboard_id', 1)
            ->firstOrFail();

        $this->branches = $panel->branches()->get();

        if ($this->branches->isEmpty()) {
            $this->resetStats();
            return;
        }

        $branch = $this->current_branch_id
            ? $this->branches->firstWhere('id', $this->current_branch_id)
            : $this->branches->first();

        if (!$branch) {
            $branch = $this->branches->first();
        }

        // اگر اولین شعبه انتخاب شده، مقدار select هم تنظیم شود
        $this->current_branch_id = $branch->id;

        $this->branch_reserves = $branch->reserves;

        $this->totalReservations = $this->branch_reserves->count();

        $this->totalIncome = $this->branch_reserves->sum(function ($reserve) {
            return $reserve->total_cost
                - ($reserve->total_cost * $reserve->discount / 100);
        });

        $this->totalDis = $this->branch_reserves->sum(function ($reserve) {
            return $reserve->total_cost
                * $reserve->discount / 100;
        });

        $this->totalCustomers = $this->branch_reserves
            ->groupBy('customer_id')
            ->count();

        $grouped = $this->branch_reserves
            ->groupBy(function ($reserve) {
                return Verta::instance($reserve->date)
                    ->format('Y/m/d');
            })
            ->sortKeys();

        $this->chartData = [
            'labels' => $grouped
                ->keys()
                ->values()
                ->toArray(),

            'values' => $grouped
                ->map(function ($reserves) {
                    return $reserves->sum('total_cost');
                })
                ->values()
                ->toArray(),
        ];

        // فقط یک event
        $this->dispatch(
            'reservation-chart-updated',
            chartData: $this->chartData
        );
    }

    public function onBranchChange()
    {
        $this->loading_data();
    }

    private function resetStats()
    {
        $this->totalReservations = 0;
        $this->totalIncome = 0;
        $this->totalDis = 0;
        $this->totalCustomers = 0;

        $this->chartData = [
            'labels' => [],
            'values' => [],
        ];

        $this->dispatch(
            'reservation-chart-updated',
            chartData: $this->chartData
        );
    }

    public function render()
    {
        return view(
            'livewire.charts.panel_charts'
        );
    }
}
