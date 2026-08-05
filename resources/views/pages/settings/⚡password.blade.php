<?php

use App\Concerns\PasswordValidationRules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

new class extends Component {
    use PasswordValidationRules;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">تنظیمات رمز عبور</flux:heading>

    <x-pages::settings.layout
        heading="به‌روزرسانی رمز عبور"
        subheading="برای حفظ امنیت حساب، از یک رمز عبور طولانی و تصادفی استفاده کنید"
    >
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">

            <flux:input
                wire:model="current_password"
                label="رمز عبور فعلی"
                type="password"
                required
                autocomplete="current-password"
            />

            <flux:input
                wire:model="password"
                label="رمز عبور جدید"
                type="password"
                required
                autocomplete="new-password"
            />

            <flux:input
                wire:model="password_confirmation"
                label="تکرار رمز عبور"
                type="password"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button
                        variant="primary"
                        type="submit"
                        class="w-full"
                        data-test="update-password-button"
                    >
                        ذخیره
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    ذخیره شد.
                </x-action-message>
            </div>
        </form>

    </x-pages::settings.layout>
</section>
