<div> @if($employee_service_list_selected) <button
    class="fixed bottom-6 left-6 h-12 bg-gray-500 text-white px-6 rounded-full shadow-lg hover:bg-blue-600 transition-colors z-50 flex items-center justify-center">
Pick </button> @endif {{-- STATE 1: نمایش سرویس‌ها --}} @if($state == 1)
            <div class="flex flex-col">
                <flux:button wire:click="switchState(0)"> بازگشت </flux:button> @foreach($branch_categories as $category) <div
                    class="mb-4"> <span
                        class="text-gray-900 dark:text-gray-100 font-semibold text-lg border-r-4 border-blue-500 pr-3">
                        {{ $category->caption }} </span>
                    <div class="mt-2 mr-4 space-y-2"> @foreach($category->services as $service) <div
                        class="text-gray-700 dark:text-gray-300 block p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                        <div class="font-medium"> {{ $service->caption }}
                            <div class="text-sm mt-1"> زمان: {{ $service->time }}<br> مبلغ: {{ $service->cost }}<br>
                                توضیحات: {{ $service->description }} </div>
                        </div> {{-- employees --}} @if($service->employees->count())
                            <div class="ml-4 mt-2 text-sm text-gray-500 space-y-1"> <strong>کارمندان:</strong>
                                <div class="flex flex-wrap gap-2 mt-1"> @foreach($service->employees as $employee) <span
                                  wire:click="select_employee({{ $employee->id }}, {{ $service->time }}, {{ $service->id }})"  class="px-2 py-1
                                  {{ $employee_selected == $employee->id ? 'border-2' : ''  }}
                                  rounded-md bg-gray-100 dark:bg-gray-700 text-xs cursor-pointer hover:bg-blue-100 dark:hover:bg-blue-900">
                                {{ $employee->name ?? $employee->caption }} </span> @endforeach </div>
                        </div> @else <div class="ml-4 mt-2 text-yellow-500 text-sm"> ❌ هیچ کارمندی برای این سرویس موجود نیست
                        </div> @endif
                    </div> @endforeach </div>
                </div> @endforeach

        </div> @endif {{-- STATE 0: نمایش شعب --}}
         @if($state == 0) @foreach($branches as $branch) <div
        class="text-center m-1 p-3 border rounded">
        <h1 class="text-right">{{ $branch->caption }}</h1>
        <div class="flex flex-col md:flex-row justify-between gap-2 mt-2">
            <flux:link> تماس </flux:link>
            <flux:button wire:click="select_branch({{ $branch->id }})"> نوبت جدید </flux:button>
        </div>
        <div class="flex flex-col md:flex-row gap-2 mt-2">
            <flux:link> موقعیت مکانی </flux:link> <span>{{ $branch->address }}</span>
        </div>
    </div> @endforeach @endif

         @if (isset($employee_selected))

                <input type="text" class="datepicker border p-2 rounded w-full"

        wire:model.live="reserve_data"
            placeholder="انتخاب تاریخ" />

          @if (isset($reserve_data))

            <flux:button wire:click="save_date">انتخاب ساعت </flux:button>

             @endif
              @endif

              <flux:modal
              wire:model="select_time_modal" >

              @if(isset($time_of_wtimes))
              @foreach($time_of_wtimes as $working_time_item)

            @for ($ind2 = 0 ; $ind2 < 4 ; $ind2++)
              <flux:button wire:click="submit_time_to_reservetion('{{$working_time_item.$list_mins[$ind2]  }}')">
                <span>{{ $working_time_item.':'.$list_mins[$ind2] }}</span>
              </flux:button>
            @endfor
              @endforeach
              @endif
              </flux:modal>
</div>
