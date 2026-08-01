
            <form wire:submit="store" 
            class="form_card"
            >
{{-- ... --}}
{{-- فرض می‌کنیم این کد داخل یک تگ <form wire:submit.prevent="store"> قرار می‌گیرد --}}

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
<flux:input
    label="موقعیت مکانی (مثال: طول و عرض جغرافیایی)"
    placeholder="مثال: 35.6892, 51.3890"
    type="text" {{-- یا type="text" --}}
    wire:model="location"
    :error="$errors->first('location')"
/>

{{-- فیلد working_times --}}
{{-- برای زمان‌های کاری، بسته به پیچیدگی، ممکن است نیاز به یک کامپوننت سفارشی‌تر یا textarea باشد --}}
<flux:input
    label="ساعات کاری"
    placeholder="مثال: شنبه تا چهارشنبه: 8:00 - 17:00"
    type="text" {{-- یا type="textarea" اگر کامپوننت flux:input از آن پشتیبانی کند --}}
    wire:model="working_times"
    :error="$errors->first('working_times')"
/>

{{-- دکمه ارسال --}}




            
                <flux:button type="submit" variant="primary">
                    ذخیره 
                </flux:button>

                

            </form>