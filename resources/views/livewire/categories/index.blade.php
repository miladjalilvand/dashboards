<div>
    {{-- کامپوننت سوئیچر شعبه --}}
    <livewire:branch_switcher />

    {{-- دکمه جدید دسته --}}

        <flux:button wire:click="open_modal"

        >
            {{ __('جدید') }}
        </flux:button>


    {{-- تعداد دسته‌ها --}}
    <div class="mt-4 text-gray-900 dark:text-gray-100">
        تعداد دسته‌ها: {{ $categories->count() }}
    </div>

    {{-- مودال افزودن/ویرایش دسته --}}
    <flux:modal
     wire:model="isopen" 
    name="categories" focusable class="max-w-lg">
        <form wire:submit.prevent="store" class="space-y-4 p-4">

            <flux:input 
                label="عنوان دسته"
                placeholder="کپشن را وارد کنید"
                type="text"
                wire:model="caption"
                :error="$errors->first('caption')"
            />

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:button 
                    type="submit" 
                    variant="filled"
                >
                    {{ $edit_mode ? 'ویرایش' : 'ذخیره' }}
                </flux:button>
                                      


                    


            </div>
        </form>
    </flux:modal>

    {{-- لیست دسته‌ها --}}
    <div class="mt-6 space-y-3">
        @foreach($categories->reverse() as $category)
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 flex justify-between items-center bg-white dark:bg-gray-800">
                <span class="text-gray-900 dark:text-gray-100">
                    عنوان: {{ $category->caption }}
                </span>
    <flux:modal.trigger name="categories">

                <flux:button 
x-data="" x-on:click.prevent="$dispatch('open-modal', 'categories')"
                    wire:click="openEdit({{ $category->id }})"
                >
                    ویرایش
                </flux:button>
                    </flux:modal.trigger>

            </div>
        @endforeach
    </div>
</div>