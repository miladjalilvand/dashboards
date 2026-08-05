<?php

use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    use ProfileValidationRules;

    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}; ?>


<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">تنظیمات پروفایل</flux:heading>

    <x-pages::settings.layout
        heading="پروفایل"
        subheading="نام و آدرس ایمیل خود را به‌روزرسانی کنید"
    >
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">

            <flux:input
                wire:model="name"
                label="نام"
                type="text"
                required
                autofocus
                autocomplete="name"
            />

            <div>
                <flux:input
                    wire:model="email"
                    label="ایمیل"
                    type="email"
                    required
                    autocomplete="email"
                />

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <flux:text class="mt-4">
                            آدرس ایمیل شما تأیید نشده است.

                            <flux:link
                                class="text-sm cursor-pointer"
                                wire:click.prevent="resendVerificationNotification"
                            >
                                برای ارسال مجدد ایمیل تأیید، اینجا کلیک کنید.
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                لینک تأیید جدید به آدرس ایمیل شما ارسال شد.
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button
                        variant="primary"
                        type="submit"
                        class="w-full"
                        data-test="update-profile-button"
                    >
                        ذخیره
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    ذخیره شد.
                </x-action-message>
            </div>
        </form>

{{--        @if ($this->showDeleteUser)--}}
{{--            <livewire:pages::settings.delete-user-form />--}}
{{--        @endif--}}
    </x-pages::settings.layout>
</section>

