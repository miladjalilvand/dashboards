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
            </span><br/> <span class="text-gray-700 dark:text-gray-300">
              تخفیف:  {{$service->discount}}%
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
                                    <flux:button
                                        variant="primary"
                                        wire:click="show_edit({{ $service->id }})"
                                    >
                                        <b>ویرایش</b>
                                    </flux:button>
                                </flux:modal.trigger>

                                <flux:button
                                    variant="primary"
                                    wire:click="openDiscountDialog({{ $service->id }})"
                                >
                                    <b>تخفیف</b>
                                </flux:button>
                            <flux:button
                                wire:click="toggleStatus({{ $service->id }})"
                                variant="{{ $service->is_active ? 'danger' : 'primary' }}"
                            >
                                {{ $service->is_active ? 'غیرفعال کردن' : 'فعال کردن' }}
                            </flux:button></div>

    </div>
    </div>
    </div>
            <flux:modal wire:model="showDiscountDialog" class="md:w-[450px]">

                <div class="space-y-6">

                    <div>
                        <flux:heading size="lg">
                            ایجاد تخفیف
                        </flux:heading>

                        <flux:text class="mt-1">
                            درصد تخفیف سرویس را مشخص کنید.
                        </flux:text>
                    </div>

                    @if($discountService)

                        {{-- قیمت اصلی --}}
                        <div class="flex items-center justify-between">
                <span class="text-zinc-500">
                    قیمت اصلی
                </span>

                            <span class="font-semibold">
                    {{ number_format($discountService->cost) }}
                </span>
                        </div>

                        {{-- Slider --}}
                        <div class="space-y-3">

                            <div class="flex items-center justify-between">
                    <span class="text-sm text-zinc-500">
                        درصد تخفیف
                    </span>

                                <span class="font-bold text-red-500">
                        {{ $discount }}٪
                    </span>
                            </div>

                            <input
                                type="range"
                                min="0"
                                max="100"
                                step="1"
                                wire:model.live="discount"
                                class="w-full accent-red-500"
                            >

                            <div class="flex justify-between text-xs text-zinc-400">
                                <span>۰٪</span>
                                <span>۵۰٪</span>
                                <span>۱۰۰٪</span>
                            </div>

                        </div>

                        {{-- قیمت نهایی --}}
                        <div class="rounded-xl bg-zinc-100 dark:bg-zinc-800 p-4">

                            <div class="flex items-center justify-between">
                    <span class="text-zinc-500 dark:text-zinc-400">
                        مبلغ نهایی
                    </span>

                                <span class="text-xl font-bold text-green-600">
                        {{ number_format($finalCost) }}
                    </span>
                            </div>

                            @if($discount > 0)
                                <div class="mt-2 text-sm text-red-500">
                                    مبلغ تخفیف:
                                    {{ number_format($discountService->cost - $finalCost) }}
                                </div>
                            @endif

                        </div>

                    @endif

                    {{-- Buttons --}}
                    <div class="flex justify-end gap-2">

                        <flux:button
                            variant="ghost"
                            wire:click="$set('showDiscountDialog', false)"
                        >
                            انصراف
                        </flux:button>

                        <flux:button
                            variant="primary"
                            wire:click="saveDiscount"
                        >
                            ذخیره تخفیف
                        </flux:button>

                    </div>

                </div>

            </flux:modal>
    @endforeach

    </div>
