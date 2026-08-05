
<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            title="بازنشانی رمز عبور"
            description="لطفاً رمز عبور جدید خود را در قسمت زیر وارد کنید"
        />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <flux:input
                name="email"
                value="{{ request('email') }}"
                label="ایمیل"
                type="email"
                required
                autocomplete="email"
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
                    data-test="reset-password-button"
                >
                    بازنشانی رمز عبور
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
