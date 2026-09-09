<div>
    <flux:modal.trigger name="branches">
        <flux:button variant="primary" x-data="" x-on:click.prevent="$dispatch('open-modal', 'branches')" data-test="delete-user-button">
            {{ __('جدید')}}
        </flux:button>
    </flux:modal.trigger>

    <div class="h-6"></div>


    <flux:modal
        name="branches"
        :show="$errors->isNotEmpty()"
        focusable
        class="w-full max-w-2xl"
        wire:model="showModal"
    >
                   <form wire:submit="store"
            class="space-y-4 p-4 bg-white dark:bg-gray-800 rounded-lg"
            >
{{-- ... --}}
{{-- فرض می‌کنیم این کد داخل یک تگ
     قرار می‌گیرد --}}

{{-- فیلد caption (که قبلاً داشتید و درست است) --}}
<flux:input
    label="عنوان"
    placeholder="کپشن را وارد کنید"
    type="text"
    wire:model="caption"
    :error="$errors->first('caption')"
/>

{{-- فیلد phone --}}
<flux:input
    label="تلفن"
    placeholder="شماره تلفن را وارد کنید"
    type="tel" {{-- type="tel" برای شماره تلفن مناسب‌تر است --}}
    wire:model="phone"
    :error="$errors->first('phone')"
/>

{{-- فیلد mobile --}}
<flux:input
    label="موبایل"
    placeholder="شماره موبایل را وارد کنید"
    type="tel" {{-- type="tel" یا type="text" --}}
    wire:model="mobile"
    :error="$errors->first('mobile')"
/>

{{-- فیلد address --}}
<flux:input
    label="آدرس"
    placeholder="آدرس کامل را وارد کنید"
    type="text"
    wire:model="address"
    :error="$errors->first('address')"
/>

{{-- فیلد location --}}
{{--<flux:input--}}
{{--    label="موقعیت مکانی (لینک گوگل مپ)"--}}
{{--    placeholder=""--}}
{{--    type="text" --}}{{-- یا type="text" --}}
{{--    wire:model="location"--}}
{{--    :error="$errors->first('location')"--}}
{{--/>--}}

{{-- فیلد working_times --}}
{{-- برای زمان‌های کاری، بسته به پیچیدگی، ممکن است نیاز به یک کامپوننت سفارشی‌تر یا textarea باشد --}}
{{--<flux:input--}}
{{--    label="توضیحات"--}}
{{--    placeholder=""--}}
{{--    type="text" --}}{{-- یا type="textarea" اگر کامپوننت flux:input از آن پشتیبانی کند --}}
{{--    wire:model="working_times"--}}
{{--    :error="$errors->first('working_times')"--}}
{{--/>--}}
                       <flux:input
    label=" کلید درگاه پرداخت "
    placeholder="xxxxx"
    type="text" {{-- یا type="textarea" اگر کامپوننت flux:input از آن پشتیبانی کند --}}
    wire:model="bank_key"
    :error="$errors->first('bank_key')"
/>

                       <button
                           type="button"
                           wire:click="openGatewayModal"
                           wire:loading.attr="disabled"
                           wire:target="openGatewayModal"
                           class="
        flex
        w-full
        cursor-pointer
        items-center
        gap-3
        rounded-lg
        border
        border-blue-200
        bg-blue-50
        px-4
        py-3
        text-right
        transition
        hover:bg-blue-100
        disabled:cursor-not-allowed
        disabled:opacity-50
        dark:border-blue-900/50
        dark:bg-blue-950/30
        dark:hover:bg-blue-950/50
    "
                       >
    <span
        class="
            flex
            h-6
            w-6
            shrink-0
            items-center
            justify-center
            rounded-full
            bg-blue-600
            text-xs
            font-bold
            text-white
        "
    >
        !
    </span>

                           <span class="flex flex-col gap-0.5">
        <span
            wire:loading.remove
            wire:target="openGatewayModal"
            class="text-sm font-semibold text-blue-900 dark:text-blue-200"
        >
             راهنمای تنظیم درگاه
        </span>

        <span
            wire:loading
            wire:target="openGatewayModal"
            class="text-sm font-semibold text-blue-900 dark:text-blue-200"
        >
            در حال آماده‌سازی...
        </span>

    </span>
                       </button>

{{-- دکمه ارسال --}}





                    <flux:button  type="submit" variant="filled">
                    {{ __('ذخیره') }}

                </flux:button>



            <!-- <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit" data-test="confirm-delete-user-button">
                    {{ __('Delete account') }}
                </flux:button>
            </div> -->
            </form>

    </flux:modal>
    @foreach($branches->reverse() as $branch)
<div class="flex flex-col">
    <div class="flex flex-col m-1 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <div>
            <span class="text-gray-900 dark:text-gray-100">
عنوان شعبه : {{$branch->caption}}
            </span>
        </div>
        <div>
            <span class="text-gray-700 dark:text-gray-300">
                آدرس : {{$branch->address}}
            </span>
        </div>
{{--        <div>--}}
{{--            <span class="text-gray-700 dark:text-gray-300">--}}
{{--                ساعات کاری : {{$branch->working_times}}--}}
{{--            </span>--}}
{{--        </div>--}}
                <div>
            <span class="text-gray-700 dark:text-gray-300">
                شماره تماس : {{$branch->phone}}
            </span>
        </div>
                <div>
            <span class="text-gray-700 dark:text-gray-300">
                موبایل : {{$branch->mobile}}
            </span>
        </div>
{{--                <div>--}}
{{--            <span class="text-gray-600 dark:text-gray-400">--}}
{{--                موقعیت مکانی : {{$branch->location}}--}}
{{--            </span>--}}
{{--        </div>--}}
<div>

            <span class="text-gray-600 dark:text-gray-400">
                درگاه بانکی : {{$branch->bank_key ? 'ثبت شده' : ''}}
            </span>
        </div>
                <div class="text-left mt-2">
 <flux:modal.trigger name="branches">
        <flux:button variant="primary"
        wire:click="show_edit({{$branch}})" >


                ویرایش

        </flux:button>

    </flux:modal.trigger>
                    <flux:button
                        wire:click="toggleStatus({{ $branch->id }})"
                        variant="{{ $branch->is_active ? 'danger' : 'primary' }}"
                    >
                        {{ $branch->is_active ? 'غیرفعال کردن' : 'فعال کردن' }}
                    </flux:button>
        </div>

    </div>
</div>
@endforeach
    <flux:modal
        name="gateway-settings"
        class="w-full max-w-md"
    >
        <div class="space-y-6">

            {{-- Header --}}
            <div>
                <h2 class="text-lg font-semibold text-zinc-900">
                    راهنمای دریافت کد درگاه
                </h2>

                <p class="mt-1 text-sm leading-6 text-zinc-500">
                    راهنمای دریافت و ثبت اطلاعات درگاه پرداخت
                </p>
            </div>


            {{-- Guide --}}
            <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">

                <div class="space-y-5">

                    {{-- Step 1 --}}
                    <div class="flex gap-3">

                        <div
                            class="
                            flex size-7 shrink-0 items-center justify-center
                            rounded-full
                            bg-zinc-900
                            text-xs font-bold text-white
                        "
                        >
                            ۱
                        </div>

                        <p class="text-sm leading-7 text-zinc-600">
                            ابتدا شعبه موردنظر خود را در سیستم ایجاد کنید.
                        </p>

                    </div>


                    {{-- Step 2 --}}
                    <div class="flex gap-3">

                        <div
                            class="
                            flex size-7 shrink-0 items-center justify-center
                            rounded-full
                            bg-zinc-900
                            text-xs font-bold text-white
                        "
                        >
                            ۲
                        </div>

                        <p class="text-sm leading-7 text-zinc-600">
                            پس از ایجاد شعبه، برای دریافت کد درگاه پرداخت
                            از طریق زرین‌پال اقدام کنید.
                        </p>

                    </div>


                    {{-- Step 3 --}}
                    <div class="flex gap-3">

                        <div
                            class="
                            flex size-7 shrink-0 items-center justify-center
                            rounded-full
                            bg-zinc-900
                            text-xs font-bold text-white
                        "
                        >
                            ۳
                        </div>

                        <p class="text-sm leading-7 text-zinc-600">
                            کد دریافت‌شده از زرین‌پال را در قسمت
                            <span class="font-semibold text-zinc-900">
                            ایجاد / ویرایش شعبه
                        </span>
                            وارد کنید.
                        </p>

                    </div>


                    {{-- Step 4 --}}
                    <div class="flex gap-3">

                        <div
                            class="
                            flex size-7 shrink-0 items-center justify-center
                            rounded-full
                            bg-zinc-900
                            text-xs font-bold text-white
                        "
                        >
                            ۴
                        </div>

                        <p class="text-sm leading-7 text-zinc-600">
                            پس از ثبت کد، درگاه پرداخت شعبه فعال شده و
                            امکان دریافت هزینه نوبت‌ها به صورت آنلاین فراهم می‌شود.
                        </p>

                    </div>

                </div>

            </div>


            {{-- Important Notice --}}
            <div
                class="
                rounded-xl
                border border-amber-200
                bg-amber-50
                p-4
            "
            >
                <div class="flex gap-3">

                    <div class="mt-0.5 shrink-0">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            class="size-5 text-amber-600"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v4m0 4h.01M10.29 3.86 2.82 17a2 2 0 0 0 1.74 3h14.88a2 2 0 0 0 1.74-3L13.71 3.86a2 2 0 0 0-3.42 0Z"
                            />
                        </svg>
                    </div>

                    <p class="text-xs leading-6 text-amber-700">
                        کد درگاه هر شعبه اختصاصی است. هنگام ثبت کد،
                        اطمینان حاصل کنید که کد مربوط به همان شعبه را وارد می‌کنید.
                    </p>

                </div>
            </div>


            {{-- Close --}}
            <flux:modal.close class="w-full">

                <button
                    type="button"
                    class="
                    w-full
                    cursor-pointer
                    rounded-lg
                    bg-zinc-900
                    py-2.5
                    text-sm font-semibold
                    text-white
                    transition
                    hover:bg-zinc-800
                "
                >
                    متوجه شدم
                </button>

            </flux:modal.close>

        </div>
    </flux:modal>
</div>
