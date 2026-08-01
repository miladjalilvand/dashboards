{{-- resources/views/components/form/input_caption.blade.php --}}
@props([
    'label',
    'name',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'errorKey' => $name ?? '', // برای نمایش خطاها، به طور پیش‌فرض از $name استفاده می‌شود
    'required' => false,
    'containerClass' => ''
])

<div class="mb-4 {{ $containerClass }}">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        {{ $label }}
        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($errorKey, $value) }}" {{-- مقدار قدیمی یا مقدار اولیه را نمایش می‌دهد --}}
        placeholder="{{ $placeholder ?: $label }}" {{-- placeholder پیش‌فرض بر اساس لیبل --}}
        {{ $attributes->merge([ 
            'class' => 'shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-white ' . ($errors->has($errorKey) ? 'border-red-300 dark:border-red-500 focus:ring-red-500 focus:border-red-500' : ''),
        ]) }}
        {{ $required ? 'required' : '' }}
    >

    {{-- نمایش خطاهای اعتبارسنجی --}}
    @error($errorKey)
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
