
<div class="w-full overflow-hidden">


    {{-- Filters --}}
    <div class="mb-6 grid grid-cols-1 md:px-6 md:gap-6 md:grid-cols-3">

        {{-- Branch --}}
        <div class="w-full">
            <flux:select

                label="انتخاب شعبه"
                wire:model.live="current_branch_id"
                wire:change="onBranchChange"
                :error="$errors->first('current_branch_id')"
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


        {{-- Reserve Filter --}}
        <div class="w-full">
            <flux:select
                label="فیلتر رزروها"
                wire:model.live="reserve_filter"
{{--                wire:change="onreserve_filter"--}}

            >
                <flux:select.option value="all">
                    همه
                </flux:select.option>

                <flux:select.option value="past">
                    تاریخ گذشته
                </flux:select.option>

                <flux:select.option value="future">
                    تاریخ آینده
                </flux:select.option>

                <flux:select.option value="approved">
                    تأیید شده
                </flux:select.option>

                <flux:select.option value="unapproved">
                    تأیید نشده
                </flux:select.option>

                <flux:select.option value="pending">
                    در انتظار
                </flux:select.option>
            </flux:select>
        </div>


        {{-- Sort --}}
        <div class="w-full">
            <flux:select
                label="مرتب‌سازی"
                wire:model.live="reserve_sort"
{{--                wire:change="onreserve_sort"--}}

            >
                <flux:select.option value="newest">
                    جدیدترین
                </flux:select.option>

                <flux:select.option value="oldest">
                    قدیمی‌ترین
                </flux:select.option>
            </flux:select>
        </div>

    </div>




    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm
                dark:border-gray-700 dark:bg-gray-900">

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
                        class="cursor-pointer transition-colors
                               hover:bg-gray-50
                               dark:hover:bg-gray-800/70"
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
                            {{ $reserve->customer?->user->name }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ \Hekmatinasser\Verta\Verta::instance($reserve->date)->format('Y/m/d') }}
                        </flux:table.cell>

                        <flux:table.cell>
                            @php
                                $start = str_pad($reserve->start_time, 4, '0', STR_PAD_LEFT);
                            @endphp
                            {{ substr($start, 0, 2) . ':' . substr($start, 2) }}
                        </flux:table.cell>

                        <flux:table.cell>
                            @php
                                $end = str_pad($reserve->end_time, 4, '0', STR_PAD_LEFT);
                            @endphp
                            {{ substr($end, 0, 2) . ':' . substr($end, 2) }}
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

                        <flux:table.cell
                            colspan="10"
                            class="py-10 text-center text-gray-500
                                   dark:text-gray-400"
                        >
                            اطلاعاتی وجود ندارد.
                        </flux:table.cell>

                    </flux:table.row>

                @endforelse

            </flux:table.rows>

        </flux:table>

    </div>


    {{-- Reserve Details Modal --}}
    <flux:modal
        name="reserve-details"
        class="w-full max-w-2xl"
    >

        @if($selectedReserve)

            <div class="space-y-6">

                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        اطلاعات رزرو
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        جزئیات کامل رزرو انتخاب‌شده
                    </p>
                </div>


                {{-- Details --}}
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4
                                dark:border-gray-700 dark:bg-gray-800/60">

                        <span class="font-semibold text-gray-700 dark:text-gray-300">
                            شعبه:
                        </span>

                        <span class="text-gray-900 dark:text-white">
                            {{ $selectedReserve->branch->caption }}
                        </span>

                    </div>


                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4
                                dark:border-gray-700 dark:bg-gray-800/60">

                        <span class="font-semibold text-gray-700 dark:text-gray-300">
                            کارمند:
                        </span>

                        <span class="text-gray-900 dark:text-white">
                            {{ $selectedReserve->employee->name }}
                        </span>

                    </div>


                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4
                                dark:border-gray-700 dark:bg-gray-800/60">

                        <span class="font-semibold text-gray-700 dark:text-gray-300">
                            مشتری:
                        </span>

                        <span class="text-gray-900 dark:text-white">
                            {{ $selectedReserve->customer?->user->name ?? '---' }}
                        </span>

                    </div>


                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4
                                dark:border-gray-700 dark:bg-gray-800/60">

                        <span class="font-semibold text-gray-700 dark:text-gray-300">
                            تاریخ:
                        </span>

                        <span class="text-gray-900 dark:text-white">
                           {{ \Hekmatinasser\Verta\Verta::instance($reserve->date)->format('Y/m/d') }}
                        </span>

                    </div>


                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4
                                dark:border-gray-700 dark:bg-gray-800/60">

                        <span class="font-semibold text-gray-700 dark:text-gray-300">
                            شروع:
                        </span>

                        <span class="text-gray-900 dark:text-white">
                              @php
                                  $start = str_pad($selectedReserve->start_time, 4, '0', STR_PAD_LEFT);
                              @endphp
                            {{ substr($start, 0, 2) . ':' . substr($start, 2) }}
                        </span>

                    </div>


                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4
                                dark:border-gray-700 dark:bg-gray-800/60">

                        <span class="font-semibold text-gray-700 dark:text-gray-300">
                            پایان:
                        </span>

                        <span class="text-gray-900 dark:text-white">
   @php
       $end = str_pad($selectedReserve->end_time, 4, '0', STR_PAD_LEFT);
   @endphp
                            {{ substr($end, 0, 2) . ':' . substr($end, 2) }}
                        </span>

                    </div>


                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4
                                dark:border-gray-700 dark:bg-gray-800/60">

                        <span class="font-semibold text-gray-700 dark:text-gray-300">
                            مدت:
                        </span>

                        <span class="text-gray-900 dark:text-white">
                            {{ $selectedReserve->total_time }} دقیقه
                        </span>

                    </div>


                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4
                                dark:border-gray-700 dark:bg-gray-800/60">

                        <span class="font-semibold text-gray-700 dark:text-gray-300">
                            مبلغ:
                        </span>

                        <span class="font-semibold text-gray-900 dark:text-white">
                            {{ number_format($selectedReserve->total_cost) }}
                        </span>

                    </div>


                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4
                                dark:border-gray-700 dark:bg-gray-800/60">

                        <span class="font-semibold text-gray-700 dark:text-gray-300">
                            تخفیف:
                        </span>

                        <span class="text-gray-900 dark:text-white">
                            {{ number_format($selectedReserve->discount) }}
                        </span>

                    </div>


                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4
                                dark:border-gray-700 dark:bg-gray-800/60">

                        <span class="font-semibold text-gray-700 dark:text-gray-300">
                            وضعیت:
                        </span>

                        <span class="text-gray-900 dark:text-white">
                            {{ $selectedReserve->status->caption }}
                        </span>

                    </div>

                </div>


                {{-- Action --}}
                @if($selectedReserve->status_id == 1)

                    <div class="border-t border-gray-200 pt-4 dark:border-gray-700">

                        <flux:button
                            wire:click="markReviewed"
                            variant="primary"
                        >
                            تغییر وضعیت به بررسی شده
                        </flux:button>

                    </div>

                @endif

            </div>

        @endif

    </flux:modal>

</div>

