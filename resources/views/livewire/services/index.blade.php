    <div>

        <livewire:branch_switcher />

        <div class="h-6"></div>
            <flux:button wire:click="open_modal"

            >
                {{ __('جدید') }}
            </flux:button>

        <div class="h-6"></div>










           <flux:modal name="services" :show="$errors->isNotEmpty()" focusable
                       class="w-full max-w-2xl"
            wire:model="isopen"
           >
                       <form wire:submit="store"
                class="space-y-4 p-4 bg-white dark:bg-gray-800 rounded-lg"
                >
    {{-- ... --}}

    {{-- فیلد caption (که قبلاً داشتید و درست است) --}}

     <flux:select
                    label="دسته‌بندی"
                    placeholder="انتخاب دسته"
                    wire:model="category_id"
                    :error="$errors->first('category_id')"
                >
                <flux:select.option value="">

                        </flux:select.option>
                    @forelse($categories ?? [] as $category)
                        <flux:select.option value="{{ $category->id }}">
                            {{ $category->caption }}
                        </flux:select.option>
                    @empty
                        <flux:select.option disabled value="">
                            هیچ دسته‌ای یافت نشد
                        </flux:select.option>
                    @endforelse
                </flux:select>

                           <flux:field>
                               <flux:label>عنوان</flux:label>

                               <flux:input
                                   placeholder="عنوان را وارد کنید"
                                   type="text"
                                   wire:model="caption"
                               />

                               <flux:error name="caption" />
                           </flux:field>

                           <flux:field>
                               <flux:label>زمان</flux:label>

                               <flux:input
                                   placeholder="زمان را وارد کنید"
                                   type="number"
                                   wire:model="time"
                               />

                               <flux:error name="time" />
                           </flux:field>

                           <flux:field>
                               <flux:label>مبلغ (تومان)</flux:label>

                               <flux:input
                                   placeholder="مبلغ را وارد کنید"
                                   type="number"
                                   wire:model="cost"
                               />

                               <flux:error name="cost" />
                           </flux:field>

                           <flux:field>
                               <flux:label>مبلغ برای ثبت نوبت (تومان)</flux:label>

                               <flux:input
                                   placeholder="مبلغ را وارد کنید"
                                   type="number"
                                   wire:model="reserve_price"

                               />

                               <flux:error name="reserve_price" />
                           </flux:field>

                           <flux:field>
                               <flux:label>توضیحات</flux:label>

                               <flux:input
                                   placeholder="توضیحات را وارد کنید"
                                   type="text"
                                   wire:model="description"
                               />

                               <flux:error name="description" />
                           </flux:field>

    {{-- دکمه ارسال --}}





                    <flux:button type="submit" variant="primary">
                        ذخیره
                    </flux:button>



                </form>


        </flux:modal>

        @foreach($services as $service)
    <div class="flex flex-col">
        <div class="flex flex-col m-1 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">

        <div class="text-gray-900 dark:text-gray-100">         <span>
              عنوان:  {{$service->caption}}
            </span><br/>
            <span class="text-gray-700 dark:text-gray-300">
              دسته‌بندی:  {{$service->category->caption}}
            </span><br/>
             <span class="text-gray-700 dark:text-gray-300">
              زمان:  {{$service->time}}
            </span><br/>
            <span class="text-gray-700 dark:text-gray-300">
    مبلغ (تومان): {{ number_format($service->cost) }} تومان
</span><br/>

            <span class="text-gray-700 dark:text-gray-300">
    مبلغ برای ثبت نوبت (تومان):
    {{ $service->reserve_price == 0 ? 'رایگان' : number_format($service->reserve_price) . ' تومان' }}
</span><br/>

             <span class="text-gray-600 dark:text-gray-400">
              توضیحات:  {{$service->description}}
            </span><br/>
        </div>
                        <div class="text-left mt-2">
                            <div>
     <flux:modal.trigger name="services">
            <flux:button variant="primary"
            wire:click="show_edit({{$service}})" >


                    ویرایش

            </flux:button>
        </flux:modal.trigger>
                            <flux:button
                                wire:click="toggleStatus({{ $service->id }})"
                                variant="{{ $service->is_active ? 'danger' : 'primary' }}"
                            >
                                {{ $service->is_active ? 'غیرفعال کردن' : 'فعال کردن' }}
                            </flux:button></div>

    </div>
    </div>
    </div>

    @endforeach
    </div>
