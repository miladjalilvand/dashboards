<?php

namespace App\Livewire\Dashboard;

use App\Models\Admin;
use App\Models\Dashboard;
use App\Models\Panel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $dashboards_list;

    public $panels_user;

    public $selected_dashboard = null;

    public $subscription_months = 1;


    public $user;


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



//        dd([
//            'dashboard_id' => $this->selected_dashboard['id'],
//
//            'dashboard' => $this->selected_dashboard['caption'],
//
//            'months' => $months,
//
//            'monthly_price' => $monthlyPrice,
//
//            'total_price' => $totalPrice,
//        ]);


        $panel = Panel::where('user_id', Auth::id())
            ->where('dashboard_id', $this->selected_dashboard['id'])
            ->first();

        if ($panel) {

            // پنل از قبل وجود دارد
            $panel->expired_date = now()->addMonth($months);
            $panel->save();

        } else {

            // پنل وجود ندارد
            $panel = Panel::create([
                'user_id' => Auth::id(),
                'website' => '',
                'expired_date' => now()->addMonth($months),
                'dashboard_id' => $this->selected_dashboard['id'],
            ]);
            Admin::create([
                'role_id' => 1,
                'panel_id' =>  $panel->id ,
                'user_id' => Auth::id(),
                'password' => '1234'
            ]);

            for($option_id = 1 ; $option_id <= 3 ; $option_id++){
                if(!Panel::where('panel_id' , $panel->id)->where('option_id' , $option_id)->exists()) {
                    PanelOption::create([
                        'panel_id' => $panel->id,
                        'option_id' => $option_id
                    ]);
                }
            }

        }

        $panel->website = 'web' . $panel->id;
        $panel->save();
        $this->render();
//        $this->dispatch('$refresh');
//        $this->js('window.location.reload()');

    }


    /*
    |--------------------------------------------------------------------------
    | Mount
    |--------------------------------------------------------------------------
    */

    public function mount()
    {

        $this->user = Auth::user();
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
