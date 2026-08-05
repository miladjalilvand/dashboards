
<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            title="تأیید رمز عبور"
            description="این بخش از برنامه امن است. لطفاً قبل از ادامه، رمز عبور خود را تأیید کنید."
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="password"
                label="رمز عبور"
                type="password"
                required
                autocomplete="current-password"
                placeholder="رمز عبور"
                viewable
            />

            <flux:button
                variant="primary"
                type="submit"
                class="w-full"
                data-test="confirm-password-button"
            >
                تأیید
            </flux:button>
        </form>
    </div>
</x-layouts::auth>
