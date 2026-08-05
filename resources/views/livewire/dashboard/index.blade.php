
<div class="w-full" dir="rtl">

    {{-- =========================================================
         DASHBOARD LIST
    ========================================================== --}}

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

        @foreach($dashboards_list as $dashboard)

            @php
                $price = (float) $dashboard['per_of_month'];
                $discount = (float) $dashboard['percentage'];

                $finalPrice = $price - (($price * $discount) / 100);
            @endphp

            <div
                class="
                    flex flex-col
                    rounded-xl
                    border border-zinc-200
                    bg-white
                    p-5
                    transition
                    hover:border-zinc-300
                    dark:border-zinc-800
                    dark:bg-zinc-900
                    dark:hover:border-zinc-700
                "
            >

                {{-- HEADER --}}
                <div class="flex items-start justify-between gap-3">

                    <div class="min-w-0">

                        <h2 class="
                            truncate
                            text-lg font-semibold
                            text-zinc-900
                            dark:text-zinc-100
                        ">
                            {{ $dashboard['caption'] }}
                        </h2>

                        <p class="
                            mt-1
                            text-xs
                            text-zinc-400
                            dark:text-zinc-500
                        ">
                            شناسه #{{ $dashboard['id'] }}
                        </p>

                    </div>

                    @if($discount > 0)
                        <span class="
                            shrink-0
                            rounded-md
                            bg-zinc-100
                            px-2 py-1
                            text-xs font-medium
                            text-zinc-600
                            dark:bg-zinc-800
                            dark:text-zinc-300
                        ">
                            {{ $discount }}٪ تخفیف
                        </span>
                    @endif

                </div>


                {{-- DESCRIPTION --}}
                <p class="
                    mt-5
                    min-h-[56px]
                    text-sm
                    leading-7
                    text-zinc-500
                    dark:text-zinc-400
                ">
                    {{ $dashboard['description'] }}
                </p>


                {{-- DIVIDER --}}
                <div class="
                    my-5
                    border-t
                    border-zinc-100
                    dark:border-zinc-800
                "></div>


                {{-- PRICE --}}
                <div>

                    @if($discount > 0)

                        <div class="
                            text-sm
                            text-zinc-400
                            line-through
                        ">
                            {{ number_format($price) }} تومان
                        </div>

                        <div class="mt-1 flex items-baseline gap-2">

                            <span class="
                                text-2xl
                                font-bold
                                tracking-tight
                                text-zinc-900
                                dark:text-white
                            ">
                                {{ number_format($finalPrice) }}
                            </span>

                            <span class="
                                text-xs
                                text-zinc-400
                            ">
                                تومان / ماه
                            </span>

                        </div>

                    @else

                        <div class="flex items-baseline gap-2">

                            <span class="
                                text-2xl
                                font-bold
                                tracking-tight
                                text-zinc-900
                                dark:text-white
                            ">
                                {{ number_format($price) }}
                            </span>

                            <span class="
                                text-xs
                                text-zinc-400
                            ">
                                تومان / ماه
                            </span>

                        </div>

                    @endif

                </div>


                {{-- FEATURES --}}
                <div class="
                    mt-5
                    space-y-2
                    text-sm
                    text-zinc-500
                    dark:text-zinc-400
                ">

                    <div class="flex items-center gap-2">
                        <span class="text-zinc-300 dark:text-zinc-600">✓</span>
                        بروزرسانی رایگان
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-zinc-300 dark:text-zinc-600">✓</span>
                        پشتیبانی فنی
                    </div>

                </div>


                {{-- ACTIONS --}}
                <div class="mt-6 space-y-2">

                    <flux:modal.trigger name="subscription">

                        <button
                            type="button"
                            wire:click="openSubscriptionModal({{ $dashboard['id'] }})"
                            class="
                                w-full
                                cursor-pointer
                                rounded-lg
                                bg-zinc-900
                                py-2.5
                                text-sm
                                font-medium
                                text-white
                                transition
                                hover:bg-zinc-800
                                dark:bg-white
                                dark:text-zinc-900
                                dark:hover:bg-zinc-200
                            "
                        >
                            خرید اشتراک
                        </button>

                    </flux:modal.trigger>


                    @if($this->is_paid($dashboard['id']))

                        <a
                            href="{{ route('reserves_dashboard.index') }}"
                            class="block"
                        >

                            <button
                                type="button"
                                class="
                                    w-full
                                    cursor-pointer
                                    rounded-lg
                                    border
                                    border-zinc-200
                                    py-2.5
                                    text-sm
                                    font-medium
                                    text-zinc-700
                                    transition
                                    hover:bg-zinc-50
                                    dark:border-zinc-700
                                    dark:text-zinc-300
                                    dark:hover:bg-zinc-800
                                "
                            >
                                ورود به پنل
                            </button>

                        </a>

                    @endif

                </div>

            </div>

        @endforeach

    </div>


    {{-- =========================================================
         SUBSCRIPTION MODAL
    ========================================================== --}}

    <flux:modal
        name="subscription"
        class="w-full max-w-md"
    >

        @if($selected_dashboard)

            @php

                $price = (float) $selected_dashboard['per_of_month'];
                $discount = (float) $selected_dashboard['percentage'];

                $monthlyPrice =
                    $price - (($price * $discount) / 100);

                $totalPrice =
                    $monthlyPrice * (int) $subscription_months;

            @endphp


            <div class="space-y-6">


                {{-- HEADER --}}
                <div>

                    <h2 class="
                        text-lg
                        font-semibold
                        text-zinc-900
                        dark:text-white
                    ">
                        خرید اشتراک
                    </h2>

                    <p class="
                        mt-1
                        text-sm
                        text-zinc-500
                        dark:text-zinc-400
                    ">
                        {{ $selected_dashboard['caption'] }}
                    </p>

                </div>


                {{-- MONTHS --}}
                <div>

                    <div class="
                        mb-4
                        flex
                        items-center
                        justify-between
                    ">

                        <span class="
                            text-sm
                            text-zinc-500
                            dark:text-zinc-400
                        ">
                            مدت اشتراک
                        </span>

                        <span class="
                            text-sm
                            font-semibold
                            text-zinc-900
                            dark:text-white
                        ">
                            {{ $subscription_months }} ماه
                        </span>

                    </div>


                    <input
                        type="range"
                        min="1"
                        max="12"
                        step="1"
                        wire:model.live="subscription_months"
                        class="
                            w-full
                            cursor-pointer
                            accent-zinc-900
                            dark:accent-white
                        "
                    >

                    <div class="
                        mt-2
                        flex
                        justify-between
                        text-xs
                        text-zinc-400
                    ">
                        <span>۱ ماه</span>
                        <span>۶ ماه</span>
                        <span>۱۲ ماه</span>
                    </div>

                </div>


                {{-- PRICE DETAILS --}}
                <div class="
                    rounded-lg
                    border
                    border-zinc-200
                    p-4
                    dark:border-zinc-800
                ">

                    <div class="space-y-3 text-sm">

                        <div class="flex justify-between">

                            <span class="text-zinc-500 dark:text-zinc-400">
                                سرویس
                            </span>

                            <span class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $selected_dashboard['caption'] }}
                            </span>

                        </div>


                        <div class="flex justify-between">

                            <span class="text-zinc-500 dark:text-zinc-400">
                                قیمت ماهانه
                            </span>

                            <span class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ number_format($monthlyPrice) }}
                                تومان
                            </span>

                        </div>


                        <div class="flex justify-between">

                            <span class="text-zinc-500 dark:text-zinc-400">
                                تعداد ماه
                            </span>

                            <span class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $subscription_months }} ماه
                            </span>

                        </div>


                        @if($discount > 0)

                            <div class="flex justify-between">

                                <span class="text-zinc-500 dark:text-zinc-400">
                                    تخفیف
                                </span>

                                <span class="font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $discount }}٪
                                </span>

                            </div>

                        @endif


                        <div class="
                            border-t
                            border-zinc-200
                            pt-3
                            dark:border-zinc-800
                        ">

                            <div class="flex items-center justify-between">

                                <span class="
                                    font-semibold
                                    text-zinc-900
                                    dark:text-white
                                ">
                                    مبلغ پرداختی
                                </span>

                                <span class="
                                    text-lg
                                    font-bold
                                    text-zinc-900
                                    dark:text-white
                                ">
                                    {{ number_format($totalPrice) }}

                                    <span class="
                                        text-xs
                                        font-normal
                                        text-zinc-400
                                    ">
                                        تومان
                                    </span>
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- PAYMENT --}}
                <button
                    type="button"
                    wire:click="paySubscription"
                    wire:loading.attr="disabled"
                    class="
                        w-full
                        cursor-pointer
                        rounded-lg
                        bg-zinc-900
                        py-3
                        text-sm
                        font-semibold
                        text-white
                        transition
                        hover:bg-zinc-800
                        disabled:opacity-50
                        dark:bg-white
                        dark:text-zinc-900
                        dark:hover:bg-zinc-200
                    "
                >

                    <span
                        wire:loading.remove
                        wire:target="paySubscription"
                    >
                        پرداخت
                    </span>

                    <span
                        wire:loading
                        wire:target="paySubscription"
                    >
                        در حال انتقال...
                    </span>

                </button>


                {{-- CLOSE --}}
                <flux:modal.close>

                    <button
                        type="button"
                        class="
                            w-full
                            cursor-pointer
                            text-sm
                            text-zinc-500
                            transition
                            hover:text-zinc-900
                            dark:text-zinc-400
                            dark:hover:text-white
                        "
                    >
                        انصراف
                    </button>

                </flux:modal.close>

            </div>

        @else

            <div class="
                p-8
                text-center
                text-sm
                text-zinc-500
            ">
                در حال بارگذاری...
            </div>

        @endif

    </flux:modal>

</div>
