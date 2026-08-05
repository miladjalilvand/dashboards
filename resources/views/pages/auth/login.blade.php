
<x-layouts::auth>
    <div class="flex flex-col gap-6">

        <x-auth-header
            title="ورود به حساب کاربری"
            description="شماره موبایل و رمز عبور خود را وارد کنید"
        />

        <!-- Session Status -->
        <x-auth-session-status
            class="text-center"
            :status="session('status')"
        />

        <form
            method="POST"
            action="{{ route('login.store') }}"
            class="flex flex-col gap-6"
        >
            @csrf

            <!-- mobile_number -->
            <flux:input
                name="email"
                :label="__('ایمیل')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    label="رمز عبور"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="رمز عبور خود را وارد کنید"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link
                        class="absolute top-0 text-sm end-0"
                        :href="route('password.request')"
                        wire:navigate
                    >
                        رمز عبور را فراموش کرده‌اید؟
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox
                name="remember"
                label="مرا به خاطر بسپار"
                :checked="old('remember')"
            />

            <!-- Login Button -->
            <div class="flex items-center justify-end">
                <flux:button
                    variant="primary"
                    type="submit"
                    class="w-full"
                    data-test="login-button"
                >
                    ورود
                </flux:button>
            </div>
        </form>

        <!-- Register -->
        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>حساب کاربری ندارید؟</span>

                <flux:link
                    :href="route('register')"
                    wire:navigate
                >
                    ثبت‌نام کنید
                </flux:link>
            </div>
        @endif

    </div>
</x-layouts::auth>
