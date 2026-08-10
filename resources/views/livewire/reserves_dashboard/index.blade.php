<div class="w-full overflow-hidden  ">
    <div
        class="rounded-2xl border border-gray-200
         dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm p-4 w-full">

        <flux:select
            label="انتخاب شعبه"
            wire:model="current_branch_id"
            :error="$errors->first('current_branch_id')"
            wire:change="onBranchChange"
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

    {{-- روزها --}}
    <div
        class="
        shadow-lg p-3 sm:p-5 ">

        <div class="  h-[120px] flex items-center gap-2 sm:gap-3 overflow-x-auto scrollbar-hide w-full">

            {{-- دکمه قبلی --}}


            {{-- لیست روزها --}}
            @foreach ($selected_days as $day)

                @php
                    $formattedDay = $day->format('Y-m-d');
                @endphp

                <button
                    wire:click="select_date('{{ $formattedDay }}')"

                    @class([
                        'group min-w-[95px] sm:min-w-[110px]
                        py-3 px-2 rounded-2xl
                        flex flex-col items-center justify-center
                        transition-all duration-300
                        border backdrop-blur-md
                        hover:scale-105 active:scale-95',

                        // حالت عادی
                        'bg-white/90 dark:bg-gray-800/90
                        border-gray-200 dark:border-gray-700
                        hover:bg-gray-100 dark:hover:bg-gray-700
                        text-gray-700 dark:text-gray-200
                        shadow-sm hover:shadow-md'
                        =>
                            $selected_date != $formattedDay
                            && $selected_date != $day,

                        // حالت انتخاب شده
                        'bg-gradient-to-br from-gray-500 to-gray-600
                        text-white
                        shadow-lg shadow-blue-500/30 scale-105'
                        =>
                            $selected_date == $formattedDay
                            || $selected_date == $day,
                    ])
                >

                    <span class="text-sm font-bold">
                        {{ verta($day)->format('l') }}
                    </span>

                    <span
                        class="text-xs mt-1 opacity-80">
                        {{ verta($day)->format('j F') }}
                    </span>

                </button>

            @endforeach

            {{-- دکمه بعدی --}}


        </div>
        <div class="flex w-full justify-center">

            <flux:button
                wire:click="change_list_days('next7')"
                class="min-w-[42px] h-[42px]
               rounded-s-full rounded-e-none
               bg-gray-100 dark:bg-gray-800
               text-gray-700 dark:text-gray-200
               hover:bg-gray-200 dark:hover:bg-gray-700
               shadow-none"
            >
                <
            </flux:button>

            <flux:button
                wire:click="change_list_days('past7')"
                class="-ms-px min-w-[42px] h-[42px]
               rounded-e-full rounded-s-none
               bg-gray-100 dark:bg-gray-800
               text-gray-700 dark:text-gray-200
               hover:bg-gray-200 dark:hover:bg-gray-700
               shadow-none"
            >
                >
            </flux:button>

        </div>
    </div>
    <div class="mt-5 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900"> <div class="overflow-x-auto"> <flux:table> {{-- Header --}} <flux:table.columns> <flux:table.column> # </flux:table.column> <flux:table.column> شعبه </flux:table.column> <flux:table.column> کارمند </flux:table.column> <flux:table.column> مشتری </flux:table.column> <flux:table.column> تاریخ </flux:table.column> <flux:table.column> شروع </flux:table.column> <flux:table.column> پایان </flux:table.column> <flux:table.column> مدت </flux:table.column> <flux:table.column> مبلغ </flux:table.column> <flux:table.column> وضعیت </flux:table.column> </flux:table.columns> {{-- Rows --}} <flux:table.rows> @forelse($reserves as $reserve) <flux:table.row wire:key="reserve-{{ $reserve->id }}" wire:click="showReserve({{ $reserve->id }})" class="cursor-pointer transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-gray-800/60" > {{-- # --}} <flux:table.cell> <span class="text-sm text-gray-500 dark:text-gray-400"> {{ $loop->iteration }} </span> </flux:table.cell> {{-- Branch --}} <flux:table.cell> <span class="font-medium text-gray-800 dark:text-gray-200"> {{ $reserve->branch->caption }} </span> </flux:table.cell> {{-- Employee --}} <flux:table.cell> <span class="text-gray-700 dark:text-gray-300"> {{ $reserve->employee->name }} </span> </flux:table.cell> {{-- Customer --}} <flux:table.cell> <span class="text-gray-700 dark:text-gray-300"> {{ $reserve->customer->user->name ?? '---' }} </span> </flux:table.cell> {{-- Date --}} <flux:table.cell> <span class="whitespace-nowrap text-gray-700 dark:text-gray-300"> {{ \Hekmatinasser\Verta\Verta::instance($reserve->date)->format('Y/m/d') }} </span> </flux:table.cell> {{-- Start --}} <flux:table.cell> <span class="font-medium text-gray-800 dark:text-gray-200">
                                                        {{ substr( $reserve->start_time, 0, 2) . ':' . substr( $reserve->start_time, 2) }}
 </span>
                        </flux:table.cell> {{-- End --}} <flux:table.cell> <span class="font-medium text-gray-800 dark:text-gray-200">
                                                        {{ substr( $reserve->end_time, 0, 2) . ':' . substr( $reserve->end_time, 2) }}

                            </span> </flux:table.cell> {{-- Duration --}} <flux:table.cell> <span class="whitespace-nowrap text-gray-700 dark:text-gray-300"> {{ $reserve->total_time }} <span class="text-xs text-gray-500 dark:text-gray-400"> دقیقه </span> </span> </flux:table.cell> {{-- Cost --}} <flux:table.cell> <span class="whitespace-nowrap font-semibold text-gray-900 dark:text-white"> {{ number_format($reserve->total_cost) }} </span> </flux:table.cell> {{-- Status --}} <flux:table.cell> @switch($reserve->status_id) @case(1) <flux:badge color="amber"> در انتظار </flux:badge> @break @case(2) <flux:badge color="green"> بررسی شده </flux:badge> @break @case(3) <flux:badge color="red"> لغو شده </flux:badge> @break @default <flux:badge> {{ $reserve->status->caption }} </flux:badge> @endswitch </flux:table.cell> </flux:table.row> @empty <flux:table.row> <flux:table.cell colspan="10" class="py-12 text-center" > <div class="flex flex-col items-center justify-center gap-2"> <span class="text-sm font-medium text-gray-600 dark:text-gray-300"> اطلاعاتی وجود ندارد. </span> <span class="text-xs text-gray-400 dark:text-gray-500"> برای این تاریخ رزروی ثبت نشده است. </span> </div> </flux:table.cell> </flux:table.row> @endforelse </flux:table.rows> </flux:table> </div> </div>
    <flux:modal name="reserve-details" class="w-full max-w-2xl">

        @if($selectedReserve)

            <div class="space-y-6">

                <h2 class="text-xl font-bold">
                    اطلاعات رزرو
                </h2>

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <span class="font-semibold">شعبه:</span>
                        {{ $selectedReserve->branch->caption }}
                    </div>

                    <div>
                        <span class="font-semibold">کارمند:</span>
                        {{ $selectedReserve->employee->name }}
                    </div>

                    <div>
                        <span class="font-semibold">مشتری:</span>
                        {{ $selectedReserve->customer?->name }}
                    </div>

                    <div>
                        <span class="font-semibold">تاریخ:</span>
                        {{ \Hekmatinasser\Verta\Verta::instance($reserve->date)->format('Y/m/d') }}
                    </div>

                    <div>
                        <span class="font-semibold">شروع:</span>

                        {{ substr( $selectedReserve->start_time, 0, 2) . ':' . substr( $selectedReserve->start_time, 2) }}

                    </div>

                    <div>
                        <span class="font-semibold">پایان:</span>
                        {{ substr($selectedReserve->end_time, 0, 2) . ':' . substr($selectedReserve->end_time, 2) }}

                    </div>

                    <div>
                        <span class="font-semibold">مدت:</span>
                        {{ $selectedReserve->total_time }} دقیقه
                    </div>

                    <div>
                        <span class="font-semibold">مبلغ:</span>
                        {{ number_format($selectedReserve->total_cost) }}
                    </div>

                    <div>
                        <span class="font-semibold">تخفیف:</span>
                        {{ number_format($selectedReserve->discount) }}
                    </div>

                    <div>
                        <span class="font-semibold">وضعیت:</span>
                        {{ $selectedReserve->status->caption }}
                    </div>

                </div>

                @if($selectedReserve->status_id == 1)

                    <flux:button
                        wire:click="markReviewed"
                        variant="primary"
                    >
                        تغییر وضعیت به بررسی شده
                    </flux:button>

                @endif

            </div>

        @endif

    </flux:modal>

</div>
