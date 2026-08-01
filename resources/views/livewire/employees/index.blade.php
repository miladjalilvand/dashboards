<div>

    <livewire:branch_switcher />


    <flux:button wire:click="open_modal">
        {{ __('جدید') }}
    </flux:button>


    @foreach($employees as $item_employee)

        <div class="flex flex-col">
            <div
                class="flex flex-col m-1 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">

                <span class="text-gray-900 dark:text-gray-100">نام : {{$item_employee->name}}</span><br />

                <div>
                    <span class="text-gray-700 dark:text-gray-300">سرویس ها </span><br />

                    @foreach($item_employee->services as $item_service_employee)
                        <span class="text-gray-600 dark:text-gray-400">{{$item_service_employee->caption}}</span></br>
                    @endforeach
                </div>

            </div>
        </div>
        <flux:button wire:click="add_service({{ $item_employee->id }})" wire:loading.attr="disabled"
            wire:target="add_service({{ $item_employee->id }})">
            افزودن سرویس
        </flux:button>

        <div class="text-left">
            <flux:modal.trigger name="employees">
                <flux:button variant="primary" wire:click="show_edit({{$item_employee}})">


                    ویرایش

                </flux:button>
            </flux:modal.trigger>
        </div>
        <flux:modal :show="$errors->isNotEmpty()" focusable class="max-w-lg" wire:model="showModalAddService">


            @foreach($services as $services_item)

                <flux:button wire:click="add_service_to_employee({{ $services_item->id }})">
                    <span
                        class="{{in_array($services_item->id, $employe_service_ids) ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}}">
                        {{$services_item->caption}}

                    </span>


                </flux:button>
            @endforeach

            <br />
            <flux:button wire:click="store_employe_service()">
                ذخیره تغیرات</flux:button>

        </flux:modal>
    @endforeach

    <flux:modal name="employees" :show="$errors->isNotEmpty()" focusable class="max-w-lg" wire:model="isopen">
        <form class="space-y-4 p-4 bg-white dark:bg-gray-800 rounded-lg">
            <flux:input label="نام" placeholder="نام را وارد کنید" type="text" wire:model="name"
                :error="$errors->first('name')" />
            <flux:input label="عنوان" placeholder="کپشن را وارد کنید" type="text" wire:model="caption"
                :error="$errors->first('caption')" />

            @foreach(farsi_week_days() as $week_day_index => $week_day)
                <flux:button wire:click="add_week_day('{{ $week_day_index }}')">
                    <span
                        class="{{in_array($week_day_index, array_keys($working_times)) ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400 dark:text-gray-600'}}">
                        {{$farsi_week_days[$week_day_index]}}</span>

                </flux:button>
            @endforeach
            <flux:modal name="showModalWeekday" :show="$errors->isNotEmpty()" focusable class="max-w-lg"
                wire:model="showModalWeekday">
                <span class="text-gray-900 dark:text-gray-100">{{$farsi_week_days[$weekday_selected]?? ''}}</span><br />
                @if(isset($working_times[$week_day_index]))
                    @foreach($working_times[$week_day_index] as $key => $item)
                        <span class="text-gray-700 dark:text-gray-300">
                            {{$item['start']['h'].':'.$item['start']['m']}}تا  {{$item['end']['h'].':'.$item['end']['m']}}
                        </span>
                        <flux:button wire:click.prevent="remove_time('{{$key}}','{{$week_day_index}}')">حذف</flux:button>
                        <br />


                @endforeach @endif
                <form class="space-y-3 mt-3">
                    {{-- فرم داخلی برای انتخاب ساعت --}}

                    <div class="flex flex-row">

                        <span>ساعت شروع</span>

                        <flux:select wire:model="min_start_selected">
                            @foreach($minutesfor_select_numbers ?? [] as $index => $min)
                                <flux:select.option value="{{ $min }}">
                                    {{ $min}}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:select wire:model="hour_start_selected">
                            @foreach($hours_for_select ?? [] as $index => $hour)
                                <flux:select.option value="{{ $hour }}">
                                    {{ $hour}}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    <div class="flex flex-row">

                        <span>ساعت پایان</span>

                        <flux:select wire:model="min_end_selected">
                            @foreach($minutesfor_select_numbers ?? [] as $index => $min)
                                <flux:select.option value="{{ $min }}">
                                    {{ $min}}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:select wire:model="hour_end_selected">
                            @foreach($hours_for_select ?? [] as $index => $hour)
                                <flux:select.option value="{{ $hour }}">
                                    {{ $hour}}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>

                    <flux:button wire:click.prevent="add_time()" {{-- استفاده از .prevent برای
                        جلوگیری از سابمیت فرم و اجرای متد ذخیره --}} variant="filled" {{-- یا استایل دلخواه --}}>
                        افزودن
                    </flux:button>
                </form>

            </flux:modal>


            <flux:button wire:click.prevent="store" variant="filled">
                {{ __('ذخیره') }}

            </flux:button>

        </form>
    </flux:modal>
</div>
