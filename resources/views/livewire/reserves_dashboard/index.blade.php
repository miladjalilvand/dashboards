<div class="w-full overflow-hidden  ">
    <div
        class="rounded-2xl border border-gray-200
         dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm p-4 w-[300px]">

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

        <div class="  h-[120px] flex items-center gap-2 sm:gap-3 overflow-x-auto scrollbar-hide">

            {{-- دکمه قبلی --}}
            <flux:button
                wire:click="change_list_days('next7')"
                class="min-w-[42px] h-[42px] rounded-full
                bg-gray-100 dark:bg-gray-800
                text-gray-700 dark:text-gray-200
                hover:bg-gray-200 dark:hover:bg-gray-700
                transition-all duration-200 shadow-sm"
            >
                <
            </flux:button>

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
            <flux:button
                wire:click="change_list_days('past7')"
                class="min-w-[42px] h-[42px] rounded-full
                bg-gray-100 dark:bg-gray-800
                text-gray-700 dark:text-gray-200
                hover:bg-gray-200 dark:hover:bg-gray-700
                transition-all duration-200 shadow-sm"
            >
                >
            </flux:button>

        </div>
    </div>
    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow">
        <flux:table>

            <flux:table.columns>
                <flux:table.column>#</flux:table.column>
                <flux:table.column>شعبه</flux:table.column>
                <flux:table.column>کارمند</flux:table.column>
                <flux:table.column>مشتری</flux:table.column>
                <flux:table.column>تاریخ</flux:table.column>
                <flux:table.column>شروع</flux:table.column>
                <flux:table.column>پایان</flux:table.column>
                <flux:table.column>مدت</flux:table.column>
                <flux:table.column>مبلغ</flux:table.column>
                <flux:table.column>وضعیت</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>

                @forelse($reserves as $reserve)

                    <flux:table.row
                        wire:key="reserve-{{ $reserve->id }}"
                        wire:click="showReserve({{ $reserve->id }})"
                        class="cursor-pointer"
                    >

                        <flux:table.cell>
                            {{ $loop->iteration }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $reserve->branch->caption }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $reserve->employee->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $reserve->customer?->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $reserve->date }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $reserve->start_time }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $reserve->end_time }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $reserve->total_time }} دقیقه
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ number_format($reserve->total_cost) }}
                        </flux:table.cell>

                        <flux:table.cell>

                            @switch($reserve->status_id)

                                @case(1)
                                    <flux:badge color="amber">
                                        در انتظار
                                    </flux:badge>
                                    @break

                                @case(2)
                                    <flux:badge color="green">
                                        بررسی شده
                                    </flux:badge>
                                    @break

                                @case(3)
                                    <flux:badge color="red">
                                        لغو شده
                                    </flux:badge>
                                    @break

                                @default
                                    <flux:badge>
                                        {{ $reserve->status->caption }}
                                    </flux:badge>

                            @endswitch

                        </flux:table.cell>

                    </flux:table.row>

                @empty

                    <flux:table.row>
                        <flux:table.cell colspan="10" class="text-center py-10">
                            اطلاعاتی وجود ندارد.
                        </flux:table.cell>
                    </flux:table.row>

                @endforelse

            </flux:table.rows>

        </flux:table>
    </div><flux:modal name="reserve-details" class="w-full max-w-2xl">

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
                        {{ $selectedReserve->date }}
                    </div>

                    <div>
                        <span class="font-semibold">شروع:</span>
                        {{ $selectedReserve->start_time }}
                    </div>

                    <div>
                        <span class="font-semibold">پایان:</span>
                        {{ $selectedReserve->end_time }}
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
