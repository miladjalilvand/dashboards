
<div class="min-h-screen pb-24"
dir="rtl"
>

    <div class="flex items-center justify-between">

        {{-- ========================================================= --}}
        {{-- Customer Authentication --}}
        {{-- ========================================================= --}}
<div>
@if($state==1)
    <button
        type="button"
        wire:click="switchState(0)"
        class="
                        px-4
                        py-2
                        rounded-xl
                        border
                        border-gray-200
                        dark:border-gray-700
                        hover:bg-gray-100
                        dark:hover:bg-gray-800

                    ">

         بازگشت

    </button>
@endif
</div>
        @if (!$logged)
<div class="p-1">

    <flux:button
        variant="primary"
        icon="arrow-right-end-on-rectangle"
        wire:click="openLoginModal"
    >
        ورود / ثبت‌نام
    </flux:button>
</div>

        @else

            <div class="flex items-center gap-3 p-1">

                {{-- User Info --}}
                <div class="flex items-center gap-3">

                    {{-- Avatar --}}
                    <div
                        class="flex items-center justify-center w-10 h-10 rounded-full
                           bg-zinc-100 dark:bg-zinc-800
                           text-sm font-semibold"
                    >
                        @php
                            $customerUser = \App\Models\CustomerUser::find($user_logged_id);
                        @endphp

                        {{ $customerUser ? mb_substr($customerUser->name, 0, 1) : '' }}
                    </div>

                    {{-- Name --}}
                    <div class="flex flex-col">

                    <span class="text-xs text-zinc-500 dark:text-zinc-400">
                        خوش آمدید
                    </span>

                        <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                        {{ $customerUser?->name }}
                    </span>

                    </div>

                </div>

                {{-- Logout --}}
                <flux:button
                    variant="ghost"
                    icon="arrow-left-start-on-rectangle"
                    wire:click="logout"
                >
                    خروج
                </flux:button>

            </div>

        @endif

    </div>


    {{-- ============================================================= --}}
    {{-- Authentication Modal --}}
    {{-- ============================================================= --}}

    <flux:modal
        name="customer-auth"
        class="w-full max-w-md"
    >

        <div class="space-y-6">

            {{-- ===================================================== --}}
            {{-- Mobile --}}
            {{-- ===================================================== --}}

            @if (!$showRegisterForm)

                <div
                    wire:key="customer-mobile-login"
                    class="space-y-6"
                >

                    {{-- Header --}}
                    <div class="text-center">

                        <div
                            class="flex items-center justify-center w-12 h-12 mx-auto mb-4
                               rounded-full
                               bg-zinc-100 dark:bg-zinc-800"
                        >
                            <flux:icon.device-phone-mobile
                                class="w-6 h-6"
                            />
                        </div>

                        <flux:heading size="lg">
                            ورود به حساب کاربری
                        </flux:heading>

                        <flux:text class="mt-2">
                            برای ورود یا ثبت‌نام، شماره موبایل خود را وارد کنید.
                        </flux:text>

                    </div>


                    {{-- Form --}}
                    <form
                        wire:submit="checkMobile"
                        class="space-y-5"
                    >

                        <flux:input
                            wire:model="input_mobile_number"
                            label="شماره موبایل"
                            type="tel"
                            inputmode="numeric"
                            dir="ltr"
                            placeholder="09123456789"
                            required
                            autofocus
                            autocomplete="tel"
                        />

                        @error('input_mobile_number')
                        <flux:text
                            color="red"
                            class="text-sm"
                        >
                            {{ $message }}
                        </flux:text>
                        @enderror


                        <flux:button
                            type="submit"
                            variant="primary"
                            class="w-full"
                            wire:loading.attr="disabled"
                        >
                        <span wire:loading.remove>
                            ادامه
                        </span>

                            <span wire:loading>
                            در حال بررسی...
                        </span>
                        </flux:button>

                    </form>

                </div>

            @else

                {{-- ================================================= --}}
                {{-- Register --}}
                {{-- ================================================= --}}

                <div
                    wire:key="customer-register"
                    class="space-y-6"
                >

                    {{-- Header --}}
                    <div class="text-center">

                        <div
                            class="flex items-center justify-center w-12 h-12 mx-auto mb-4
                               rounded-full
                               bg-zinc-100 dark:bg-zinc-800"
                        >
                            <flux:icon.user-plus
                                class="w-6 h-6"
                            />
                        </div>

                        <flux:heading size="lg">
                            ایجاد حساب کاربری
                        </flux:heading>

                        <flux:text class="mt-2">
                            این شماره موبایل ثبت نشده است.
                            برای ایجاد حساب، نام خود را وارد کنید.
                        </flux:text>

                    </div>


                    {{-- Register Form --}}
                    <form
                        wire:submit="submitRegister"
                        class="space-y-5"
                    >

                        {{-- Name --}}
                        <flux:input
                            wire:model="input_name"
                            label="نام و نام خانوادگی"
                            type="text"
                            placeholder="مثلاً میلاد جلیلیوند"
                            required
                            autofocus
                            autocomplete="name"
                        />

                        {{-- Mobile --}}
                        <flux:input
                            wire:model="input_mobile_number"
                            label="شماره موبایل"
                            type="tel"
                            dir="ltr"
                            readonly
                        />


                        {{-- Buttons --}}
                        <div class="flex gap-3 pt-2">

                            <flux:button
                                type="button"
                                variant="ghost"
                                wire:click="backToMobile"
                                class="flex-1"
                            >
                                بازگشت
                            </flux:button>

                            <flux:button
                                type="submit"
                                variant="primary"
                                wire:loading.attr="disabled"
                                class="flex-1"
                            >
                            <span wire:loading.remove>
                                ثبت نام
                            </span>

                                <span wire:loading>
                                در حال ثبت...
                            </span>
                            </flux:button>

                        </div>

                    </form>

                </div>

            @endif

        </div>

    </flux:modal>
    {{-- =========================================================
         MESSAGES
    ========================================================== --}}

    @if($error_message)

        <div class="fixed top-4 left-1/2 -translate-x-1/2 z-[100] w-[92%] max-w-lg">

            <div class="flex items-start gap-3 rounded-2xl
                        border border-red-200
                        bg-red-50
                        dark:bg-red-950/50
                        dark:border-red-900
                        p-4 shadow-xl">



                <div class="flex-1">

                    <div class="font-semibold text-red-800 dark:text-red-200">
                        خطا
                    </div>

                    <div class="text-sm text-red-700 dark:text-red-300 mt-1">
                        {{ $error_message }}
                    </div>

                </div>

                <button
                    type="button"
                    wire:click="$set('error_message', null)"
                    class="text-red-400 hover:text-red-600 text-lg">

                    ×

                </button>

            </div>

        </div>

    @endif


    @if($success_message)

        <div class="fixed top-4 left-1/2 -translate-x-1/2 z-[100] w-[92%] max-w-lg">

            <div class="flex items-center gap-3 rounded-2xl
                        border border-green-200
                        bg-green-50
                        dark:bg-green-950/50
                        dark:border-green-900
                        p-4 shadow-xl">

                <div class="text-xl">
                    ✅
                </div>

                <div class="flex-1">

                    <div class="font-semibold text-green-800 dark:text-green-200">
                        موفق
                    </div>

                    <div class="text-sm text-green-700 dark:text-green-300 mt-1">
                        {{ $success_message }}
                    </div>

                </div>

                <button
                    type="button"
                    wire:click="$set('success_message', null)"
                    class="text-green-400 hover:text-green-600 text-lg">

                    ×

                </button>

            </div>

        </div>

    @endif



    {{-- =========================================================
         FIXED BOOKING BAR
    ========================================================== --}}

    @if($employee_selected)

        <div class="fixed top-10 left-0 right-0 z-50
                    bg-white/95 dark:bg-gray-900/95
                    backdrop-blur-md
                    border-b border-gray-200
                    dark:border-gray-700
                    shadow-md">

            <div class="max-w-5xl mx-auto px-4 py-3">

                <div class="flex flex-col md:flex-row
                            md:items-end gap-3">

                    {{-- Selected Employee --}}

                    <div class="md:min-w-[180px]">

                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                            کارمند
                        </div>

                        <div class="font-semibold text-gray-900 dark:text-white">

                            {{ $selected_employee?->name
                                ?? $selected_employee?->caption
                                ?? '-' }}

                        </div>

                    </div>


                    {{-- Selected Service --}}

                    <div class="md:min-w-[180px]">

                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                            سرویس
                        </div>

                        <div class="font-semibold text-gray-900 dark:text-white">

                            {{ $selected_service?->caption ?? '-' }}

                        </div>

                    </div>


                    {{-- Date --}}

                    <div class="flex-1">

                        <label class="block text-xs font-medium
                                      text-gray-500 dark:text-gray-400 mb-1">

                            تاریخ نوبت

                        </label>

                        <input
                            type="text"
                            class="datepicker w-full
                                   border border-gray-300
                                   dark:border-gray-600
                                   bg-gray-50
                                   dark:bg-gray-800
                                   text-gray-900
                                   dark:text-white
                                   p-2.5
                                   rounded-xl
                                   outline-none
                                   focus:ring-2
                                   focus:ring-blue-500"
                            wire:model.live="reserve_data"
                            placeholder="انتخاب تاریخ"
                        >

                    </div>


                    {{-- Select Time Button --}}

                    <div>
                        @if($reserve_data)
                        <button
                            type="button"
                            wire:click="save_date"
                            class="w-full md:w-auto
                                   px-5 py-2.5
                                   rounded-xl


                                   font-medium
                                   transition
                                   shadow-sm">

                            🕐 انتخاب ساعت

                        </button>


@endif
                    </div>

                </div>

            </div>

        </div>


        {{-- Fixed bar space --}}

        <div class="h-32"></div>

    @endif



    {{-- =========================================================
         STATE 0 - BRANCHES
    ========================================================== --}}

    @if($state == 0)

        <div class="max-w-5xl mx-auto px-4">

            <div class="mb-6">

                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    انتخاب شعبه
                </h1>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    شعبه مورد نظر خود را انتخاب کنید
                </p>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                @foreach($branches as $branch)

                    <div
                        class="
        group relative overflow-hidden
        rounded-3xl
        border border-gray-200 dark:border-gray-700
        bg-white dark:bg-gray-800
        shadow-sm
        transition-all duration-300
        hover:-translate-y-1
        hover:shadow-xl
    "
                    >
                        {{-- Header --}}
                        <div class="p-5 pb-4">

                            <div class="flex items-start gap-4">

                                {{-- Branch Icon --}}
                                <div
                                    class="
                    flex h-12 w-12 shrink-0 items-center justify-center
                    rounded-2xl
                    bg-blue-50 dark:bg-blue-900/30
                    text-2xl
                    transition-transform duration-300
                    group-hover:scale-110
                "
                                >
                                    🏪
                                </div>

                                <div class="min-w-0 flex-1">

                                    <h2
                                        class="
                        truncate
                        text-lg font-bold
                        text-gray-900 dark:text-white
                    "
                                    >
                                        {{ $branch->caption }}
                                    </h2>

                                    @if($branch->address)
                                        <div
                                            class="
                            mt-2 flex items-start gap-1.5
                            text-sm
                            text-gray-500 dark:text-gray-400
                        "
                                        >
                                            <span class="shrink-0">📍</span>

                                            <span class="line-clamp-2">
                            {{ $branch->address }}
                        </span>
                                        </div>
                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- Contact --}}
                        @if($branch->phone || $branch->mobile)

                            <div
                                class="
                mx-5
                rounded-2xl
                bg-gray-50 dark:bg-gray-700/40
                p-3
            "
                            >

                                <div class="mb-2 text-xs font-medium text-gray-400">
                                    تماس با شعبه
                                </div>

                                <div class="flex flex-wrap gap-2">

                                    @if($branch->phone)
                                        <a
                                            href="tel:{{ $branch->phone }}"
                                            class="
                            inline-flex items-center gap-2
                            rounded-xl
                            bg-white dark:bg-gray-800
                            px-3 py-2
                            text-sm font-medium
                            text-gray-700 dark:text-gray-200
                            shadow-sm
                            transition-all duration-200
                            hover:-translate-y-0.5
                            hover:shadow-md
                        "
                                        >
                        <span
                            class="
                                flex h-7 w-7 items-center justify-center
                                rounded-lg
                                bg-green-50 dark:bg-green-900/30
                            "
                        >
                            ☎️
                        </span>

                                            {{ $branch->phone }}
                                        </a>
                                    @endif

                                    @if($branch->mobile)
                                        <a
                                            href="tel:{{ $branch->mobile }}"
                                            class="
                            inline-flex items-center gap-2
                            rounded-xl
                            bg-white dark:bg-gray-800
                            px-3 py-2
                            text-sm font-medium
                            text-gray-700 dark:text-gray-200
                            shadow-sm
                            transition-all duration-200
                            hover:-translate-y-0.5
                            hover:shadow-md
                        "
                                        >
                        <span
                            class="
                                flex h-7 w-7 items-center justify-center
                                rounded-lg
                                bg-green-50 dark:bg-green-900/30
                            "
                        >
                            📱
                        </span>

                                            {{ $branch->mobile }}
                                        </a>
                                    @endif

                                </div>

                            </div>

                        @endif


                        {{-- Action --}}
                        <div class="p-5 pt-4">

                            <button
                                type="button"
                                wire:click="select_branch({{ $branch->id }})"
                                wire:loading.attr="disabled"
                                class="
                group/button
                flex w-full items-center justify-center gap-2
                rounded-2xl

                px-5 py-3
                text-sm font-bold
                shadow-sm
                transition-all duration-200

                hover:shadow-lg
                active:scale-[0.98]
                disabled:opacity-60
            "
                            >

            <span>
                نوبت جدید
            </span>

                                <span
                                    class="
                    transition-transform duration-200
                    group-hover/button:-translate-x-1
                "
                                >
                ←
            </span>

                            </button>

                        </div>

                    </div>

                @endforeach

            </div>
<br/>
<br/>
@if($current_customer_id)
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

                    @forelse($customer_reserves ?? [] as $reserve)

                        <flux:table.row
                            wire:key="reserve-{{ $reserve->id }}"
{{--                            wire:click="showReserve({{ $reserve->id }})"--}}
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
                                {{ substr($reserve->start_time, 0, 2) . ':' . substr($reserve->start_time, 2) }}
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ substr($reserve->end_time, 0, 2) . ':' . substr($reserve->end_time, 2) }}


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

@endif
        </div>

    @endif



    {{-- =========================================================
         STATE 1 - SERVICES
    ========================================================== --}}

    @if($state == 1)

        <div class="max-w-5xl mx-auto px-4">

            {{-- Branch Header --}}

            <div class="
                flex
                items-center
                justify-between
                gap-3
                mb-6
            ">

                <div>

                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        شعبه انتخاب شده
                    </div>

                    <h1 class="
                        text-2xl
                        font-bold
                        text-gray-900
                        dark:text-white
                        mt-1
                    ">

                        {{ $branch_selected?->caption ?? '-' }}

                    </h1>

                </div>




            </div>



            {{-- Categories --}}

            <div class="space-y-8">

                @foreach($branch_categories as $category)
@if($category->is_active)
                    <div>

                        {{-- Category --}}

                        <div class="mb-4">

                            <span class="
                                text-lg
                                font-bold
                                text-gray-900
                                dark:text-white
                                border-r-4
                                border-blue-500
                                pr-3
                            ">

                                {{ $category->caption }}

                            </span>

                        </div>


                        {{-- Services --}}

                        <div class="space-y-4">

                            @foreach($category->services as $service)
                                @if($service->is_active)

                                <div class="
                                    rounded-2xl
                                    border
                                    border-gray-200
                                    dark:border-gray-700
                                    bg-white
                                    dark:bg-gray-800
                                    p-5
                                    transition
                                    hover:shadow-md
                                ">

                                    <div class="
                                        flex
                                        flex-col
                                        md:flex-row
                                        md:justify-between
                                        gap-4
                                    ">

                                        {{-- Service info --}}

                                        <div class="flex-1">

                                            <h3 class="
                                                text-lg
                                                font-bold
                                                text-gray-900
                                                dark:text-white
                                            ">

                                                {{ $service->caption }}

                                            </h3>


                                            <div class="
                                                grid
                                                grid-cols-2
                                                md:grid-cols-3
                                                gap-3
                                                mt-3
                                                text-sm
                                            ">

                                                <div class="
                                                    rounded-xl
                                                    bg-gray-50
                                                    dark:bg-gray-700/50
                                                    p-3
                                                ">

                                                    <div class="text-xs text-gray-400">
                                                        زمان
                                                    </div>

                                                    <div class="font-semibold mt-1">
                                                        {{ $service->time }} دقیقه
                                                    </div>

                                                </div>


                                                <div class="
                                                    rounded-xl
                                                    bg-gray-50
                                                    dark:bg-gray-700/50
                                                    p-3
                                                ">

                                                    <div class="text-xs text-gray-400">
                                                        مبلغ
                                                    </div>

                                                    <div class="font-semibold mt-1">
                                                        {{ number_format($service->cost) }}
                                                    </div>

                                                </div>
<div class="
                                                    rounded-xl
                                                    bg-gray-50
                                                    dark:bg-gray-700/50
                                                    p-3
                                                ">

                                                    <div class="text-xs text-gray-400">
                                                         مبلغ برای ثبت نوبت
                                                    </div>

                                                    <div class="font-semibold mt-1">
                                                        {{ number_format($service->reserve_price) }}
                                                    </div>

                                                </div>

                                            </div>

@endif
                                            @if($service->description)

                                                <p class="
                                                    text-sm
                                                    text-gray-500
                                                    dark:text-gray-400
                                                    mt-3
                                                ">

                                                    {{ $service->description }}

                                                </p>

                                            @endif

                                        </div>


                                        {{-- Employees --}}

                                        <div class="md:w-[300px]">

                                            <div class="
                                                text-sm
                                                font-semibold
                                                text-gray-700
                                                dark:text-gray-300
                                                mb-2
                                            ">

                                                انتخاب کارمند

                                            </div>


                                            @if($service->employees->count())

                                                <div class="flex flex-wrap gap-2">

                                                    @foreach($service->employees as $employee)

                                                        @if($employee->is_active)
                                                        <button

                                                            type="button"
                                                            wire:click="select_employee(
                                                                {{ $employee->id }},
                                                                {{ $service->time }},
                                                                {{ $service->id }}
                                                            )"
                                                            class="
                                                                px-3
                                                                py-2
                                                                rounded-xl
                                                                text-sm
                                                                border
                                                                transition
                                                                hover:border-blue-500
                                                                hover:bg-blue-50
                                                                dark:hover:bg-blue-950

                                                                {{ $employee_selected == $employee->id
                                                                    && $selected_service?->id == $service->id
                                                                    ? 'border-blue-500 bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300'
                                                                    : 'border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700'
                                                                }}
                                                            "
                                                        >

                                                            👤
                                                            {{ $employee->name
                                                                ?? $employee->caption
                                                                ?? 'بدون نام' }}

                                                        </button>
@endif
                                                    @endforeach

                                                </div>

                                            @else

                                                <div class="
                                                    text-sm
                                                    text-yellow-600
                                                    dark:text-yellow-400
                                                ">

                                                    ❌ هیچ کارمندی برای این سرویس موجود نیست

                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            @endforeach
                                @endif

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    @endif



    {{-- =========================================================
         TIME MODAL
    ========================================================== --}}

    <flux:modal
        wire:model="select_time_modal"
        class="w-full max-w-lg">

        <div class="space-y-5">

            @if($error_message)

                <div class="fixed top-4 left-1/2 -translate-x-1/2 z-[100] w-[92%] max-w-lg">

                    <div class="flex items-start gap-3 rounded-2xl
                        border border-red-200
                        bg-red-50
                        dark:bg-red-950/50
                        dark:border-red-900
                        p-4 shadow-xl">



                        <div class="flex-1">

                            <div class="font-semibold text-red-800 dark:text-red-200">
                                خطا
                            </div>

                            <div class="text-sm text-red-700 dark:text-red-300 mt-1">
                                {{ $error_message }}
                            </div>

                        </div>

                        <button
                            type="button"
                            wire:click="$set('error_message', null)"
                            class="text-red-400 hover:text-red-600 text-lg">

                            ×

                        </button>

                    </div>

                </div>

            @endif

            {{-- Header --}}

            <div>

                <h2 class="
                    text-xl
                    font-bold
                    text-gray-900
                    dark:text-white
                ">

                    🕐 انتخاب ساعت نوبت

                </h2>

                <p class="
                    text-sm
                    text-gray-500
                    dark:text-gray-400
                    mt-1
                ">

                    یکی از ساعت‌های آزاد را انتخاب کنید

                </p>

            </div>


            {{-- Selected info --}}

            <div class="
                rounded-2xl
                bg-blue-50
                dark:bg-blue-950/40
                border border-blue-100
                dark:border-blue-900
                p-4
                space-y-2
            ">

                <div class="flex justify-between text-sm">

                    <span class="text-gray-500">
                        شعبه
                    </span>

                    <span class="font-semibold">
                        {{ $branch_selected?->caption ?? '-' }}
                    </span>

                </div>


                <div class="flex justify-between text-sm">

                    <span class="text-gray-500">
                        سرویس
                    </span>

                    <span class="font-semibold">
                        {{ $selected_service?->caption ?? '-' }}
                    </span>

                </div>


                <div class="flex justify-between text-sm">

                    <span class="text-gray-500">
                        کارمند
                    </span>

                    <span class="font-semibold">
                        {{ $selected_employee?->name
                            ?? $selected_employee?->caption
                            ?? '-' }}
                    </span>

                </div>


                <div class="flex justify-between text-sm">

                    <span class="text-gray-500">
                        تاریخ
                    </span>

                    <span class="font-semibold">
                        {{ $reserve_data ?? '-' }}
                    </span>

                </div>

            </div>



            {{-- Time slots --}}

            @if(count($time_of_wtimes))

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">

                    @foreach($time_of_wtimes as $slot)

                        <button
                            type="button"
                            wire:click="submit_time_to_reservetion('{{ $slot['start'] }}')"
                            class="
                                group
                                rounded-2xl
                                border
                                border-gray-200
                                dark:border-gray-700
                                bg-white
                                dark:bg-gray-800
                                p-4
                                text-center
                                transition
                                hover:border-blue-500
                                hover:bg-blue-50
                                dark:hover:bg-blue-950
                                hover:shadow-md
                                active:scale-95
                            "
                        >

                            <div class="
                                text-lg
                                font-bold
                                text-gray-900
                                dark:text-white
                                group-hover:text-blue-600
                            ">

                                {{ substr($slot['start'], 0, 2) }}:{{
                                    substr($slot['start'], 2, 2)
                                }}

                            </div>


                            <div class="
                                text-xs
                                text-gray-400
                                mt-1
                            ">

{{--                                تا--}}

{{--                                {{ substr($slot['end'], 0, 2) }}:{{--}}
{{--                                    substr($slot['end'], 2, 2)--}}
{{--                                }}--}}

                            </div>

                        </button>

                    @endforeach

                </div>

            @else

                <div class="
                    text-center
                    py-8
                    text-gray-500
                ">

                    ❌ ساعت آزادی برای این تاریخ وجود ندارد.

                </div>

            @endif

        </div>

    </flux:modal>



    {{-- =========================================================
         CONFIRM RESERVATION MODAL
    ========================================================== --}}

    <flux:modal
        wire:model="confirm_reservation_modal"
        class="w-full max-w-lg">

        <div class="space-y-5">

            {{-- Header --}}

            @if($error_message)

                <div class="fixed top-4 left-1/2 -translate-x-1/2 z-[100] w-[92%] max-w-lg">

                    <div class="flex items-start gap-3 rounded-2xl
                        border border-red-200
                        bg-red-50
                        dark:bg-red-950/50
                        dark:border-red-900
                        p-4 shadow-xl">



                        <div class="flex-1">

                            <div class="font-semibold text-red-800 dark:text-red-200">
                                خطا
                            </div>

                            <div class="text-sm text-red-700 dark:text-red-300 mt-1">
                                {{ $error_message }}
                            </div>

                        </div>

                        <button
                            type="button"
                            wire:click="$set('error_message', null)"
                            class="text-red-400 hover:text-red-600 text-lg">

                            ×

                        </button>

                    </div>

                </div>

            @endif

            <div class="text-center">

                <div class="
                    mx-auto
                    w-16
                    h-16
                    rounded-full
                    bg-blue-100
                    dark:bg-blue-950
                    flex
                    items-center
                    justify-center
                    text-3xl
                ">

<!--                    📅-->

                </div>


                <h2 class="
                    text-xl
                    font-bold
                    text-gray-900
                    dark:text-white
                    mt-3
                ">

                    تأیید نهایی نوبت

                </h2>


                <p class="
                    text-sm
                    text-gray-500
                    mt-1
                ">

                    لطفاً اطلاعات زیر را بررسی کنید

                </p>

            </div>



            {{-- Information --}}

            <div class="
                overflow-hidden
                rounded-2xl
                border
                border-gray-200
                dark:border-gray-700
            ">

                {{-- Branch --}}

                <div class="
                    flex
                    justify-between
                    gap-4
                    px-4
                    py-3
                    bg-gray-50
                    dark:bg-gray-800
                ">

                    <span class="text-sm text-gray-500">
                        شعبه
                    </span>

                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ $branch_selected?->caption ?? '-' }}
                    </span>

                </div>


                {{-- Service --}}

                <div class="
                    flex
                    justify-between
                    gap-4
                    px-4
                    py-3
                ">

                    <span class="text-sm text-gray-500">
                        سرویس
                    </span>

                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ $selected_service?->caption ?? '-' }}
                    </span>

                </div>


                {{-- Employee --}}

                <div class="
                    flex
                    justify-between
                    gap-4
                    px-4
                    py-3
                    bg-gray-50
                    dark:bg-gray-800
                ">

                    <span class="text-sm text-gray-500">
                        کارمند
                    </span>

                    <span class="font-semibold text-gray-900 dark:text-white">

                        {{ $selected_employee?->name
                            ?? $selected_employee?->caption
                            ?? '-' }}

                    </span>

                </div>


                {{-- Date --}}

                <div class="
                    flex
                    justify-between
                    gap-4
                    px-4
                    py-3
                ">

                    <span class="text-sm text-gray-500">
                        تاریخ
                    </span>

                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ $reserve_data ?? '-' }}
                    </span>

                </div>


                {{-- Time --}}

                <div class="
                    flex
                    justify-between
                    gap-4
                    px-4
                    py-3
                    bg-gray-50
                    dark:bg-gray-800
                ">

                    <span class="text-sm text-gray-500">
                        ساعت
                    </span>

                    <span class="
                        font-bold
                        text-blue-600
                        dark:text-blue-400
                    ">

                        @if($selected_time)

                            {{ substr($selected_time, 0, 2) }}:{{
                                substr($selected_time, 2, 2)
                            }}

                            تا

                            {{ substr($selected_end_time, 0, 2) }}:{{
                                substr($selected_end_time, 2, 2)
                            }}

                        @else

                            -

                        @endif

                    </span>

                </div>


                {{-- Duration --}}

                <div class="
                    flex
                    justify-between
                    gap-4
                    px-4
                    py-3
                ">

                    <span class="text-sm text-gray-500">
                        مدت
                    </span>

                    <span class="font-semibold">
                        {{ $total_time }} دقیقه
                    </span>

                </div>


                {{-- Cost --}}

                <div class="
                    flex
                    justify-between
                    gap-4
                    px-4
                    py-3
                    bg-gray-50
                    dark:bg-gray-800
                ">

                    <span class="text-sm text-gray-500">
                        مبلغ
                    </span>

                    <span class="
                        font-bold
                        text-gray-900
                        dark:text-white
                    ">

                        {{ number_format($selected_service?->cost ?? 0) }}

                        تومان

                    </span>

                </div>  <div class="
                    flex
                    justify-between
                    gap-4
                    px-4
                    py-3
                    bg-gray-50
                    dark:bg-gray-800
                ">

                    <span class="text-sm text-gray-500">
                        مبلغ
                    </span>

                    <span class="
                        font-bold
                        text-gray-900
                        dark:text-white
                    ">

                        {{ $selected_service?->reserve_price ? number_format($selected_service?->reserve_price  ?? 0) : "رایگان"}}

                        {{                      $selected_service?->reserve_price ?   "تومان" : ""}}
                    </span>

                </div>

            </div>



            {{-- Buttons --}}

            <div class="flex gap-3">

                <button
                    type="button"
                    wire:click="$set('confirm_reservation_modal', false)"
                    class="
                        flex-1
                        px-4
                        py-3
                        rounded-xl
                        border
                        border-gray-200
                        dark:border-gray-700
                        hover:bg-gray-100
                        dark:hover:bg-gray-800
                    ">

                    بازگشت

                </button>


                @if($selected_service?->reserve_price == 0)
                    <button
                        type="button"
                        wire:click="confirmReservation"
                        wire:loading.attr="disabled"
                        class="
                        flex-1
                        px-4
                        py-3
                        rounded-xl
                        bg-blue-600
                        hover:bg-blue-700
                        text-white
                        font-semibold
                        disabled:opacity-50
                    ">

                    <span wire:loading.remove wire:target="confirmReservation">
                        ✓ تأیید و ثبت
                    </span>

                        <span wire:loading wire:target="confirmReservation">
                        در حال ثبت...
                    </span>

                    </button>
                    @else
                    <button
                        type="button"
                        wire:click="confirmReservation"
                        wire:loading.attr="disabled"
                        class="
                        flex-1
                        px-4
                        py-3
                        rounded-xl
                        bg-blue-600
                        hover:bg-blue-700
                        text-white
                        font-semibold
                        disabled:opacity-50
                    ">

                    <span wire:loading.remove wire:target="confirmReservation">
                       پرداخت
                    </span>

                        <span wire:loading wire:target="confirmReservation">
                        در حال ثبت...
                    </span>

                    </button>
                @endif

            </div>

        </div>

    </flux:modal>

</div>
