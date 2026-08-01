<?php

use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component {
    //
    public Branch $current_branch;

    public $t;

    public $current_branch_id;
    public $branches;


    public function mount()
    {

        // dd(Auth::user()->admin->panel->branches->first());
        // set_current_branch(Auth::user()->admin->panel->branches->first());

        $this->branches = Branch::all();
        $this->current_branch_id = current_branch()->id;
        $this->current_branch = current_branch();
    }

    public function branch_switcher()
    {

        set_current_branch(Branch::find($this->current_branch_id));

        //     if(current_branch() == Auth::user()->admin->panel->branches->last() || 
        //     $this->t=='c'){
        //         $this->t='b';
        //   set_current_branch(Auth::user()->admin->panel->branches->first()) ;
        //     }else {   set_current_branch( Auth::user()->admin->panel->branches->last());
        //         $this->t='c';

        //     }


        // dd(current_branch());
        $this->current_branch = current_branch();


        $this->dispatch('branch-switched');

     

    }
};
?>

<div>


    <flux:select label="انتخاب شعبه" wire:model="current_branch_id" :error="$errors->first('current_branch_id')"

    wire:change="branch_switcher"
        >
        @forelse($branches ?? [] as $branch)
            <flux:select.option value="{{ $branch->id }}">
                {{ $branch->caption }}
            </flux:select.option>
        @empty
            <flux:select.option disabled value="">
                هیچ شعبه‌ای یافت نشد
            </flux:select.option>
        @endforelse
    </flux:select>
</div>