<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Validation\Rule;
trait ProfileValidationRules
{
    protected function profileRules(?int $userId = null): array
    {
        return [
            'name' => $this->nameRules(),
            'email' => $this->emailRules($userId),
            'mobile_number' => $this->mobileNumberRules($userId),
        ];
    }

    protected function nameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    protected function emailRules(?int $userId = null): array
    {
        return [
            'required',
            'string',
            'email',
            'max:255',
            $userId === null
                ? Rule::unique(User::class, 'email')
                : Rule::unique(User::class, 'email')->ignore($userId),
        ];
    }

    protected function mobileNumberRules(?int $userId = null): array
    {
        return [
            'required',
            'string',
            'regex:/^09\d{9}$/',
            'max:11',
            $userId === null
                ? Rule::unique(User::class, 'mobile_number')
                : Rule::unique(User::class, 'mobile_number')->ignore($userId),
        ];
    }
}
