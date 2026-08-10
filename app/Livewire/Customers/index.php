<?php

namespace App\Livewire\Customers;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Flux\Flux;

#[Layout('layouts.app')]
class Index extends Component
{
    public $branches;
    public $branch_selected;
    public $branch_customers;
    public $current_branch_id;

    // Customer form
    public $customer_name = '';
    public $customer_mobile = '';
    public $customer_email = '';
    public $customer_password = '';

    public function mount()
    {
        $this->branches = Auth::user()
            ->panels()
            ->where('dashboard_id', 1)
            ->first()
            ->branches;

        $this->branch_selected = $this->branches->first();

        $this->current_branch_id = $this->branch_selected?->id;

        $this->branch_customers = $this->branch_selected?->customers;
    }

    public function render()
    {
        return view('livewire.customers.index');
    }

    public function onBranchChange()
    {
        $this->branch_selected = Branch::find($this->current_branch_id);

        $this->branch_customers = $this->branch_selected?->customers;
    }

    public function openCustomerModal()
    {
        $this->reset([
            'customer_name',
            'customer_mobile',
            'customer_email',
            'customer_password',
        ]);

        $this->resetValidation();

        Flux::modal('create-customer')->show();
    }

    public function createCustomer()
    {
        $validated = $this->validate([
            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'customer_mobile' => [
                'required',
                'string',
                'max:20',
                'unique:users,mobile_number',
            ],

            'customer_email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'customer_password' => [
                'required',
                'string',
                'min:6',
            ],
        ], [
            'customer_name.required' => 'نام مشتری را وارد کنید.',

            'customer_mobile.required' => 'شماره موبایل را وارد کنید.',
            'customer_mobile.unique' => 'این شماره موبایل قبلاً ثبت شده است.',

            'customer_email.email' => 'ایمیل وارد شده معتبر نیست.',
            'customer_email.unique' => 'این ایمیل قبلاً ثبت شده است.',

            'customer_password.required' => 'رمز عبور را وارد کنید.',
            'customer_password.min' => 'رمز عبور باید حداقل ۶ کاراکتر باشد.',
        ]);

        DB::transaction(function () use ($validated) {

            $user = User::create([
                'name' => $validated['customer_name'],
                'email' => $validated['customer_email'] ?: null,
                'password' => Hash::make($validated['customer_password']),
                'role_id' => 2,
                'mobile_number' => $validated['customer_mobile'],
            ]);

            $user = Auth::user();
            Customer::create([
                'user_id' => $user->id,
                'branch_id' => $this->current_branch_id,
                'panel_id' => $user->panels()->where('dashboard_id' , 1)->first()->id,
            ]);
        });

        // Refresh customers
        $this->branch_selected = Branch::find($this->current_branch_id);

        $this->branch_customers = $this->branch_selected->customers;

        // Close modal
        Flux::modal('create-customer')->close();

        // Clear form
        $this->reset([
            'customer_name',
            'customer_mobile',
            'customer_email',
            'customer_password',
        ]);

        session()->flash('success', 'مشتری با موفقیت ایجاد شد.');
    }
}
