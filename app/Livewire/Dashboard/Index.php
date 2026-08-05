<?php

namespace App\Livewire\Dashboard;

use App\Models\Dashboard;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $dashboards_list;

    public $panels_user;

    public $selected_dashboard = null;

    public $subscription_months = 1;


    /*
    |--------------------------------------------------------------------------
    | Select Dashboard
    |--------------------------------------------------------------------------
    */

    public function openSubscriptionModal($dashboardId)
    {
        $dashboard = collect($this->dashboards_list)
            ->firstWhere('id', $dashboardId);

        if (!$dashboard) {
            return;
        }

        $this->selected_dashboard = $dashboard;

        $this->subscription_months = 1;
    }


    /*
    |--------------------------------------------------------------------------
    | Pay Subscription
    |--------------------------------------------------------------------------
    */

    public function paySubscription()
    {
        if (!$this->selected_dashboard) {
            return;
        }

        $months = (int) $this->subscription_months;

        if ($months < 1 || $months > 12) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Price
        |--------------------------------------------------------------------------
        */

        $price = (float) $this->selected_dashboard['per_of_month'];

        $discount = (float) $this->selected_dashboard['percentage'];


        /*
        |--------------------------------------------------------------------------
        | Monthly Price After Discount
        |--------------------------------------------------------------------------
        */

        $monthlyPrice = $price - (
                ($price * $discount) / 100
            );


        /*
        |--------------------------------------------------------------------------
        | Total Price
        |--------------------------------------------------------------------------
        */

        $totalPrice = $monthlyPrice * $months;


        /*
        |--------------------------------------------------------------------------
        | Payment
        |--------------------------------------------------------------------------
        */

        dd([
            'dashboard_id' => $this->selected_dashboard['id'],

            'dashboard' => $this->selected_dashboard['caption'],

            'months' => $months,

            'monthly_price' => $monthlyPrice,

            'total_price' => $totalPrice,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Mount
    |--------------------------------------------------------------------------
    */

    public function mount()
    {
        $this->dashboards_list = Dashboard::all();

        $this->panels_user = Auth::user()
            ->panels()
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Is Paid
    |--------------------------------------------------------------------------
    */

    public function is_paid($dashboard_id)
    {
        return $this->panels_user
            ->where('dashboard_id', $dashboard_id)
            ->isNotEmpty();
    }


    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view(
            'livewire.dashboard.index'
        )->layout('layouts.blank');
    }
}
