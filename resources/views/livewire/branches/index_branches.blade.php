
<div class="min-h-screen pb-24">

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

                <div class="text-xl">
                    ❌
                </div>

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

        <div class="fixed top-0 left-0
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

                        <button
                            type="button"
                            wire:click="save_date"
                            class="w-full md:w-auto
                                   px-5 py-2.5
                                   rounded-xl
                                   bg-blue-600
                                   hover:bg-blue-700
                                   text-white
                                   font-medium
                                   transition
                                   shadow-sm">

                            🕐 انتخاب ساعت

                        </button>

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

                    <div class="
                        rounded-2xl
                        border border-gray-200
                        dark:border-gray-700
                        bg-white
                        dark:bg-gray-800
                        p-5
                        shadow-sm
                        hover:shadow-md
                        transition
                    ">

                        <div class="flex items-start justify-between gap-3">

                            <div>

                                <h2 class="
                                    text-lg
                                    font-bold
                                    text-gray-900
                                    dark:text-white
                                ">

                                    {{ $branch->caption }}

                                </h2>

                                @if($branch->address)

                                    <p class="
                                        text-sm
                                        text-gray-500
                                        dark:text-gray-400
                                        mt-2
                                    ">

                                        📍 {{ $branch->address }}

                                    </p>

                                @endif

                            </div>

                        </div>


                        <div class="flex gap-2 mt-5">

                            <button
                                type="button"
                                class="flex-1
                                       px-4 py-2.5
                                       rounded-xl
                                       border border-gray-200
                                       dark:border-gray-600
                                       text-gray-700
                                       dark:text-gray-200
                                       hover:bg-gray-100
                                       dark:hover:bg-gray-700">

                                تماس

                            </button>


                            <button
                                type="button"
                                wire:click="select_branch({{ $branch->id }})"
                                class="flex-1
                                       px-4 py-2.5
                                       rounded-xl
                                       bg-blue-600
                                       hover:bg-blue-700
                                       text-white
                                       font-medium">

                                نوبت جدید

                            </button>

                        </div>

                    </div>

                @endforeach

            </div>

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

                    ← بازگشت

                </button>

            </div>



            {{-- Categories --}}

            <div class="space-y-8">

                @foreach($branch_categories as $category)

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

                                            </div>


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

                    📅

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
                    <flux:select
                        label="انتخاب مشتری"
                        wire:model="current_customer"
                        :error="$errors->first('current_customer')"
                        wire:change="onCustomerChange"
                    >
                        @forelse($customers ?? [] as $customer)
                            <flux:select.option value="{{ $customer->id }}">
                                {{ $customer->user->name }}
                            </flux:select.option>
                        @empty
                            <flux:select.option disabled value="">
                                هیچ نشتری یافت نشد
                            </flux:select.option>
                        @endforelse
                    </flux:select>
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

            </div>

        </div>

    </flux:modal>

</div>
