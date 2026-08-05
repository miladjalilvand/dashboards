
<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            title="فراموشی رمز عبور"
            description="ایمیل خود را وارد کنید تا لینک بازیابی رمز عبور برای شما ارسال شود"
        />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                label="آدرس ایمیل"
                type="email"
                required
                autofocus
                placeholder="email@example.com"
            />

            <flux:button
                variant="primary"
                type="submit"
                class="w-full"
                data-test="email-password-reset-link-button"
            >
                ارسال لینک بازیابی رمز عبور
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
            <span>یا بازگشت به</span>

            <flux:link :href="route('login')" wire:navigate>
                ورود
            </flux:link>
        </div>
    </div>
</x-layouts::auth>
