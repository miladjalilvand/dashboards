<?php
namespace App\Livewire\Category;

use App\Models\Branch;
use App\Models\Category;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]

 class Index extends Component
{

  public $categories;
    public $current_branch;
    public $current_category;
    public $caption;
    public $edit_mode = false;
    public $isopen = false;

    public function refresh(){
        //   dd('refresh called');

        $this->current_branch = current_branch();

        $this->loadCategories();
    }
    public function mount()
    {
        // فرض می‌کنیم branch_switcher مقدار current_branch را set می‌کند
        $this->current_branch = current_branch();

        $this->loadCategories();

    }

        public function open_modal()
    {
      $this->edit_mode = false;

      $this->isopen = true;

    }
    #[On('branch-switched')]public function loadBranches()
    {
        $this->current_branch = current_branch();
            // dd($this->current_branch->categories);

            $this->loadCategories();
    }

       public function loadCategories()
    {

        $this->categories = $this->current_branch->categories()->get();
            // dd($this->current_branch->categories);
            $this->dispatch('close-modal', ['name' => 'categories']);


    }
    protected function rules(): array
    {
        return [


            'caption' => ['required', 'string', 'min:2', 'max:255'],



        ];
    }

    public function toggleStatus($category_id)
    {
        $category = Category::findOrFail($category_id);

        $category->update([
            'is_active' => ! $category->is_active,
        ]);

        $this->refresh();
    }
    protected function messages(): array
    {
        return [

            'caption.required' => 'عنوان  الزامی است.',
            'caption.min' => 'عنوان باید حداقل ۲ کاراکتر باشد.',


        ];
    }
public function store()
    {
        $this->validate();
        $data = [
            'branch_id' => $this->current_branch->id,
            'caption'   => $this->caption,
        ];

        if ($this->edit_mode && $this->current_category) {
            $this->current_category->update($data);
        } else {
            Category::create($data);
        }

        // پاک کردن فرم و بستن مودال



        // بستن مودال به‌صورت Flux event

        // بروزرسانی لیست
        $this->loadCategories();
        Flux::modal('categories')->close();  $this->isopen = false;
        $this->edit_mode = false;



    }

    public function openEdit($id)
    {
        $this->current_category = Category::find($id);

        if ($this->current_category) {
            $this->caption = $this->current_category->caption;
            $this->edit_mode = true;
        }


    }


        public function render()
    {

        return view("livewire.categories.index");
    }





};
