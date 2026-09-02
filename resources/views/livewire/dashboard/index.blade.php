<div class="w-full" dir="rtl">

    {{-- =========================================================
         USER MENU
    ========================================================== --}}

    <flux:header>
        <flux:spacer inset="left" />
        <flux:spacer />

        <flux:dropdown position="top" align="end">

            <flux:profile
                :initials="auth()->user()->initials()"
                icon-trailing="chevron-down"
            />

            <flux:menu>

                <flux:menu.radio.group>

                    <div class="p-0 text-sm font-normal">

                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">

                            <flux:avatar
                                :name="auth()->user()->name"
                                :initials="auth()->user()->initials()"
                            />

                            <div class="grid flex-1 text-start text-sm leading-tight">

                                <flux:heading class="truncate">
                                    {{ auth()->user()->name }}
                                </flux:heading>

                                <flux:text class="truncate">
                                    {{ auth()->user()->email }}
                                </flux:text>

                            </div>

                        </div>

                    </div>

                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    {{-- Profile --}}
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="w-full"
                >
                    @csrf

                    <flux:menu.item
                        as="button"
                        type="submit"
                        icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer"
                        data-test="logout-button"
                    >
                        خروج
                    </flux:menu.item>

                </form>

            </flux:menu>

        </flux:dropdown>

    </flux:header>


    {{-- =========================================================
         DASHBOARD LIST
    ========================================================== --}}

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

        @foreach($dashboards_list as $dashboard)

            @php
                $price = (float) $dashboard['per_of_month'];
                $discount = (float) $dashboard['percentage'];

                $finalPrice = $price - (($price * $discount) / 100);

                $panel = $this->user->getDashboardPanel($dashboard['id']);
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

                {{-- =================================================
                     HEADER
                ================================================== --}}

                <div class="flex items-start justify-between gap-3">

                    <div class="min-w-0">

                        {{-- Website --}}
                        @if($panel)

                            <a
                                href="https://{{ $panel->website }}.abc.test/12"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="
                                    group
                                    mb-4
                                    inline-flex
                                    items-center
                                    gap-3
                                    rounded-xl
                                    border
                                    border-zinc-200
                                    bg-white
                                    px-4
                                    py-3
                                    shadow-sm
                                    transition-all
                                    duration-200
                                    hover:-translate-y-0.5
                                    hover:border-zinc-300
                                    hover:shadow-md
                                    dark:border-zinc-700
                                    dark:bg-zinc-900
                                    dark:hover:border-zinc-600
                                "
                            >

                                <div
                                    class="
                                        flex
                                        size-9
                                        items-center
                                        justify-center
                                        rounded-lg
                                        bg-zinc-100
                                        transition
                                        group-hover:bg-zinc-900
                                        dark:bg-zinc-800
                                        dark:group-hover:bg-white
                                    "
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        class="
                                            size-4
                                            text-zinc-700
                                            transition
                                            group-hover:text-white
                                            dark:text-zinc-300
                                            dark:group-hover:text-zinc-900
                                        "
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M13.5 6H19.5V12M19 6.5L12 13.5M19 13.5V18A1.5 1.5 0 0 1 17.5 19.5H6A1.5 1.5 0 0 1 4.5 18V6.5A1.5 1.5 0 0 1 6 5h4.5"
                                        />
                                    </svg>

                                </div>

                                <div class="text-right">

                                    <div class="text-sm font-semibold text-zinc-900 dark:text-white">
                                        مشاهده سایت
                                    </div>

                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ $panel->website }}.abc.test
                                    </div>

                                </div>

                            </a>

                        @endif


                        {{-- Dashboard name --}}
                        <h2
                            class="
                                truncate
                                text-lg
                                font-semibold
                                text-zinc-900
                                dark:text-zinc-100
                            "
                        >
                            {{ $dashboard['caption'] }}
                        </h2>


                        <p
                            class="
                                mt-1
                                text-xs
                                text-zinc-400
                                dark:text-zinc-500
                            "
                        >
                            شناسه #{{ $dashboard['id'] }}
                        </p>

                    </div>


                    {{-- Discount --}}
                    @if($discount > 0)

                        <span
                            class="
                                shrink-0
                                rounded-md
                                bg-zinc-100
                                px-2
                                py-1
                                text-xs
                                font-medium
                                text-zinc-600
                                dark:bg-zinc-800
                                dark:text-zinc-300
                            "
                        >
                            {{ $discount }}٪ تخفیف
                        </span>

                    @endif

                </div>


                {{-- =================================================
                     DESCRIPTION
                ================================================== --}}

                <p
                    class="
                        mt-5
                        min-h-[56px]
                        text-sm
                        leading-7
                        text-zinc-500
                        dark:text-zinc-400
                    "
                >
                    {{ $dashboard['description'] }}
                </p>


                {{-- Divider --}}
                <div
                    class="
                        my-5
                        border-t
                        border-zinc-100
                        dark:border-zinc-800
                    "
                ></div>


                {{-- =================================================
                     PRICE
                ================================================== --}}

                <div>

                    @if($discount > 0)

                        <div class="text-sm text-zinc-400 line-through">
                            {{ number_format($price) }} تومان
                        </div>

                        <div class="mt-1 flex items-baseline gap-2">

                            <span
                                class="
                                    text-2xl
                                    font-bold
                                    tracking-tight
                                    text-zinc-900
                                    dark:text-white
                                "
                            >
                                {{ number_format($finalPrice) }}
                            </span>

                            <span class="text-xs text-zinc-400">
                                تومان / ماه
                            </span>

                        </div>

                    @else

                        <div class="flex items-baseline gap-2">

                            <span
                                class="
                                    text-2xl
                                    font-bold
                                    tracking-tight
                                    text-zinc-900
                                    dark:text-white
                                "
                            >
                                {{ number_format($price) }}
                            </span>

                            <span class="text-xs text-zinc-400">
                                تومان / ماه
                            </span>

                        </div>

                    @endif

                </div>


                {{-- =================================================
                     FEATURES
                ================================================== --}}

                <div
                    class="
                        mt-5
                        space-y-2
                        text-sm
                        text-zinc-500
                        dark:text-zinc-400
                    "
                >

                    <div class="flex items-center gap-2">
                        <span class="text-zinc-300 dark:text-zinc-600">
                            ✓
                        </span>
                        بروزرسانی رایگان
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-zinc-300 dark:text-zinc-600">
                            ✓
                        </span>
                        پشتیبانی فنی
                    </div>

                </div>


                {{-- =================================================
                     ACTIONS
                ================================================== --}}

                <div class="mt-6 space-y-2">


                    {{-- =================================================
                         BUY SUBSCRIPTION
                    ================================================== --}}

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


                    {{-- =================================================
                         ENTER PANEL
                    ================================================== --}}

                    @if($panel)

                        <a
                            href="{{ route('reserves_dashboard.index') }}"
                            class="block mt-3"
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


                        <button
                            type="button"
                            wire:click="openGatewayModal({{ $dashboard['id'] }})"
                            wire:loading.attr="disabled"
                            wire:target="openGatewayModal"
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
                            disabled:cursor-not-allowed
                            disabled:opacity-50
                            dark:bg-white
                            dark:text-zinc-900
                            dark:hover:bg-zinc-200
                        "
                        >

                        <span
                            wire:loading.remove
                            wire:target="openGatewayModal"
                        >
                            تنظیم درگاه
                        </span>

                            <span
                                wire:loading
                                wire:target="openGatewayModal"
                            >
                            در حال آماده‌سازی...
                        </span>

                        </button>


                        {{-- Expire date --}}
                        <div>

                            <p
                                class="
                                    mt-2
                                    text-xs
                                    text-zinc-400
                                    dark:text-zinc-500
                                "
                            >
                                تاریخ انقضا:
                                {{ verta($panel->expired_date)->format('Y/m/d') }}
                            </p>

                        </div>

                    @endif


                    {{-- =================================================
                         GATEWAY SETTINGS
                    ================================================== --}}


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

                $monthlyPrice = $price - (($price * $discount) / 100);

                $totalPrice = $monthlyPrice * (int) $subscription_months;

            @endphp


            <div class="space-y-6">

                {{-- Header --}}
                <div>

                    <h2
                        class="
                            text-lg
                            font-semibold
                            text-zinc-900
                            dark:text-white
                        "
                    >
                        خرید اشتراک
                    </h2>

                    <p
                        class="
                            mt-1
                            text-sm
                            text-zinc-500
                            dark:text-zinc-400
                        "
                    >
                        {{ $selected_dashboard['caption'] }}
                    </p>

                </div>


                {{-- Months --}}
                <div>

                    <div class="mb-4 flex items-center justify-between">

                        <span class="text-sm text-zinc-500 dark:text-zinc-400">
                            مدت اشتراک
                        </span>

                        <span
                            class="
                                text-sm
                                font-semibold
                                text-zinc-900
                                dark:text-white
                            "
                        >
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


                    <div
                        class="
                            mt-2
                            flex
                            justify-between
                            text-xs
                            text-zinc-400
                        "
                    >
                        <span>۱ ماه</span>
                        <span>۶ ماه</span>
                        <span>۱۲ ماه</span>
                    </div>

                </div>


                {{-- Price details --}}
                <div
                    class="
                        rounded-lg
                        border
                        border-zinc-200
                        p-4
                        dark:border-zinc-800
                    "
                >

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


                        <div
                            class="
                                border-t
                                border-zinc-200
                                pt-3
                                dark:border-zinc-800
                            "
                        >

                            <div class="flex items-center justify-between">

                                <span
                                    class="
                                        font-semibold
                                        text-zinc-900
                                        dark:text-white
                                    "
                                >
                                    مبلغ پرداختی
                                </span>

                                <span
                                    class="
                                        text-lg
                                        font-bold
                                        text-zinc-900
                                        dark:text-white
                                    "
                                >
                                    {{ number_format($totalPrice) }}

                                    <span
                                        class="
                                            text-xs
                                            font-normal
                                            text-zinc-400
                                        "
                                    >
                                        تومان
                                    </span>

                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Payment --}}
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


                {{-- Close --}}
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

            <div
                class="
                    p-8
                    text-center
                    text-sm
                    text-zinc-500
                "
            >
                در حال بارگذاری...
            </div>

        @endif

    </flux:modal>


    {{-- =========================================================
         GATEWAY SETTINGS MODAL
    ========================================================== --}}

    <flux:modal
        name="gateway-settings"
        class="w-full max-w-md"
    >

        <div class="space-y-6">


            {{-- Header --}}
            <div>

                <h2
                    class="
                        text-lg
                        font-semibold
                        text-zinc-900
                        dark:text-white
                    "
                >
                    تنظیمات درگاه
                </h2>

                <p
                    class="
                        mt-1
                        text-sm
                        leading-6
                        text-zinc-500
                        dark:text-zinc-400
                    "
                >
                    تنظیمات درگاه پرداخت
                    <span class="font-medium text-zinc-700 dark:text-zinc-300">
                        {{ $selected_dashboard['caption'] ?? '' }}
                    </span>
                    را وارد کنید.
                </p>

            </div>


            {{-- Gateway key --}}
            <div>

                <label
                    for="gateway_key"
                    class="
                        mb-2
                        block
                        text-sm
                        font-medium
                        text-zinc-700
                        dark:text-zinc-300
                    "
                >
                    کلید / تنظیمات درگاه
                </label>


                <input
                    id="gateway_key"
                    type="password"
                    wire:model.defer="gateway_key"
                    autocomplete="new-password"
                    placeholder="تنظیمات درگاه را وارد کنید"
                    class="
                        w-full
                        rounded-lg
                        border
                        border-zinc-200
                        bg-white
                        px-3
                        py-2.5
                        text-sm
                        text-zinc-900
                        outline-none
                        transition
                        focus:border-zinc-400
                        focus:ring-2
                        focus:ring-zinc-200
                        dark:border-zinc-700
                        dark:bg-zinc-900
                        dark:text-white
                        dark:focus:border-zinc-500
                        dark:focus:ring-zinc-800
                    "
                />


                @error('gateway_key')

                <p class="mt-2 text-xs text-red-500">
                    {{ $message }}
                </p>

                @enderror


                <p
                    class="
                        mt-2
                        text-xs
                        leading-6
                        text-zinc-400
                        dark:text-zinc-500
                    "
                >
                    این مقدار به صورت رمزنگاری‌شده ذخیره خواهد شد و برای
                    پردازش پرداخت‌های پنل استفاده می‌شود.
                </p>

            </div>


            {{-- Security notice --}}
            <div
                class="
                    rounded-lg
                    border
                    border-amber-200
                    bg-amber-50
                    p-3
                    dark:border-amber-900/50
                    dark:bg-amber-950/30
                "
            >

                <div class="flex gap-2">

                    <div class="mt-0.5 shrink-0">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            class="size-4 text-amber-600 dark:text-amber-400"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v4m0 4h.01M10.29 3.86 2.82 17a2 2 0 0 0 1.74 3h14.88a2 2 0 0 0 1.74-3L13.71 3.86a2 2 0 0 0-3.42 0Z"
                            />
                        </svg>

                    </div>


                    <p
                        class="
                            text-xs
                            leading-6
                            text-amber-700
                            dark:text-amber-300
                        "
                    >
                        این اطلاعات حساس است. فقط اطلاعات مربوط به درگاه
                        پرداخت را وارد کنید.
                    </p>

                </div>

            </div>


            {{-- Actions --}}
            <div class="flex gap-3">


                {{-- Cancel --}}
                <flux:modal.close class="flex-1">

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
                        انصراف
                    </button>

                </flux:modal.close>


                {{-- Save --}}
                <button
                    type="button"
                    wire:click="saveGatewaySettings"
                    wire:loading.attr="disabled"
                    wire:target="saveGatewaySettings"
                    class="
                        flex-1
                        cursor-pointer
                        rounded-lg
                        bg-zinc-900
                        py-2.5
                        text-sm
                        font-semibold
                        text-white
                        transition
                        hover:bg-zinc-800
                        disabled:cursor-not-allowed
                        disabled:opacity-50
                        dark:bg-white
                        dark:text-zinc-900
                        dark:hover:bg-zinc-200
                    "
                >

                    <span
                        wire:loading.remove
                        wire:target="saveGatewaySettings"
                    >
                        ذخیره تنظیمات
                    </span>

                    <span
                        wire:loading
                        wire:target="saveGatewaySettings"
                    >
                        در حال ذخیره...
                    </span>

                </button>

            </div>

        </div>

    </flux:modal>

</div>
