<?php
namespace App\Livewire\Branch;

use App\Models\Branch;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]

 class Edit extends Component
{
    // 1. پراپرتی $branch برای نگهداری مدل
    public Branch $branch;

    // 2. تعریف پراپرتی $caption برای اتصال wire:model
    public $caption; 

    public function mount(Branch $branch)
    {
        $this->branch = $branch;
        
        // 3. مقدار اولیه $caption را از مدل $branch پر می‌کنیم
        // فرض می‌کنیم فیلد مورد نظر شما در مدل Branch، 'caption' نام دارد.
        $this->caption = $this->branch->caption; 
    }

    public function render()
    {
        return view("livewire.branches.edit");
    }
    
    public function store()
    {
        // در اینجا، $this->caption حاوی مقداری است که کاربر در فیلد ورودی وارد کرده است
        // dd($this->caption); // این تست حالا باید مقدار صحیح را نشان دهد

        // کد ذخیره‌سازی واقعی شما:
        // $this->branch->caption = $this->caption;
        // $this->branch->save();
    }
}
