
<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            title="ایجاد حساب کاربری"
            description="اطلاعات زیر را برای ایجاد حساب کاربری وارد کنید"
        />

        <!-- Session Status -->
        <x-auth-session-status
            class="text-center"
            :status="session('status')"
        />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Name -->
            <flux:input
                name="name"
                label="نام"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                placeholder="نام و نام خانوادگی"
            />
            <flux:input
                name="mobile_number"
                label="شماره موبایل"
                :value="old('mobile_number')"
                type="tel"
                required
                autofocus
                autocomplete="tel"
                placeholder="09123456789"
            />
            <!-- Email Address -->
            <flux:input
                name="email"
                label="آدرس ایمیل"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                label="رمز عبور"
                type="password"
                required
                autocomplete="new-password"
                placeholder="رمز عبور"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                label="تکرار رمز عبور"
                type="password"
                required
                autocomplete="new-password"
                placeholder="تکرار رمز عبور"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button
                    type="submit"
                    variant="primary"
                    class="w-full"
                    data-test="register-user-button"
                >
                    ایجاد حساب کاربری
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>قبلاً حساب کاربری دارید؟</span>

            <flux:link :href="route('login')" wire:navigate>
                وارد شوید
            </flux:link>
        </div>
    </div>
</x-layouts::auth>
