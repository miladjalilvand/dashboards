<div class="text-zinc-900 dark:text-zinc-100">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">

        <div>

            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">
                مدیران پنل
            </h2>

            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                مدیریت مدیران و سطح دسترسی آن‌ها
            </p>

        </div>


        <flux:button
            variant="primary"
            wire:click="openCreateDialog"
            disabled
        >
            + افزودن مدیر
        </flux:button>

    </div>


    {{-- ========================================================= --}}
    {{-- Admin List --}}
    {{-- ========================================================= --}}

    <div class="
        overflow-hidden
        rounded-2xl
        border
        border-zinc-200
        dark:border-zinc-800
        bg-white
        dark:bg-zinc-900
        shadow-sm
    ">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="
                    bg-zinc-50
                    dark:bg-zinc-800/60
                    border-b
                    border-zinc-200
                    dark:border-zinc-800
                ">

                <tr>

                    <th class="px-5 py-4 text-right font-semibold">
                        مدیر
                    </th>

                    <th class="px-5 py-4 text-right font-semibold">
                        ایمیل
                    </th>

                    <th class="px-5 py-4 text-right font-semibold">
                        موبایل
                    </th>

                    <th class="px-5 py-4 text-right font-semibold">
                        وضعیت
                    </th>

                    <th class="px-5 py-4 text-right font-semibold">
                        عملیات
                    </th>

                </tr>

                </thead>


                <tbody class="
                    divide-y
                    divide-zinc-200
                    dark:divide-zinc-800
                ">

                @forelse($admins as $admin)

                    <tr
                        wire:key="admin-{{ $admin['id'] }}"
                        class="
                            transition
                            hover:bg-zinc-50
                            dark:hover:bg-zinc-800/40
                        "
                    >

                        {{-- Name --}}

                        <td class="px-5 py-4">

                            <div class="flex items-center gap-3">

                                <div class="
                                    flex
                                    h-10
                                    w-10
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-xl
                                    bg-indigo-100
                                    dark:bg-indigo-500/10
                                    text-indigo-600
                                    dark:text-indigo-400
                                    font-bold
                                ">
                                    {{ mb_substr($admin['name'], 0, 1) }}
                                </div>

                                <div>

                                    <div class="font-semibold">
                                        {{ $admin['name'] }}
                                    </div>

                                    <div class="
                                        text-xs
                                        text-zinc-500
                                        dark:text-zinc-400
                                        mt-0.5
                                    ">
                                        مدیر پنل
                                    </div>

                                </div>

                            </div>

                        </td>


                        {{-- Email --}}

                        <td class="
                            px-5
                            py-4
                            text-zinc-600
                            dark:text-zinc-300
                        ">
                            {{ $admin['email'] }}
                        </td>


                        {{-- Mobile --}}

                        <td class="
                            px-5
                            py-4
                            text-zinc-600
                            dark:text-zinc-300
                        ">
                            {{ $admin['mobile_number'] ?: '---' }}
                        </td>


                        {{-- Status --}}

                        <td class="px-5 py-4">

                            @if($admin['is_active'])

                                <span class="
                                    inline-flex
                                    items-center
                                    gap-1.5
                                    rounded-full
                                    bg-emerald-100
                                    dark:bg-emerald-500/10
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    text-emerald-700
                                    dark:text-emerald-400
                                ">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    فعال
                                </span>

                            @else

                                <span class="
                                    inline-flex
                                    items-center
                                    gap-1.5
                                    rounded-full
                                    bg-red-100
                                    dark:bg-red-500/10
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    text-red-700
                                    dark:text-red-400
                                ">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                    غیرفعال
                                </span>

                            @endif

                        </td>


                        {{-- Actions --}}

                        <td class="px-5 py-4">

                            <div class="flex flex-wrap gap-2">

                                <flux:button
                                    size="sm"
                                    wire:click="openEditDialog({{ $admin['id'] }})"
                                >
                                    ویرایش
                                </flux:button>


                                <flux:button
                                    size="sm"
                                    wire:click="openPermissionDialog({{ $admin['id'] }})"
                                >
                                    دسترسی‌ها
                                </flux:button>


                                @if($admin['is_active'])

                                    <flux:button
                                        size="sm"
                                        variant="danger"
                                        wire:click="toggleActive({{ $admin['id'] }})"
                                        wire:confirm="آیا می‌خواهید این مدیر غیرفعال شود؟"
                                    >
                                        غیرفعال
                                    </flux:button>

                                @else

                                    <flux:button
                                        size="sm"
                                        variant="primary"
                                        wire:click="toggleActive({{ $admin['id'] }})"
                                    >
                                        فعال کردن
                                    </flux:button>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="px-5 py-16 text-center"
                        >

                            <div class="text-4xl mb-3">
                                👨‍💼
                            </div>

                            <div class="font-semibold">
                                هنوز مدیری ثبت نشده است
                            </div>

                            <p class="
                                mt-1
                                text-sm
                                text-zinc-500
                                dark:text-zinc-400
                            ">
                                اولین مدیر پنل را ایجاد کنید.
                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Create / Edit Admin Dialog --}}
    {{-- ========================================================= --}}

    <flux:modal
        wire:model="showAdminDialog"
        class="md:w-[600px]"
    >

        <div class="space-y-6">

            <div>

                <flux:heading size="lg">
                    {{ $editingId ? 'ویرایش مدیر' : 'افزودن مدیر جدید' }}
                </flux:heading>

                <flux:text class="mt-1">
                    اطلاعات مدیر پنل را وارد کنید.
                </flux:text>

            </div>


            <div class="space-y-5">

                {{-- Name --}}

                <flux:input
                    label="نام و نام خانوادگی"
                    placeholder="مثلاً میلاد جلیلیوند"
                    wire:model="name"
                />

                @error('name')
                <div class="text-sm text-red-500">
                    {{ $message }}
                </div>
                @enderror


                {{-- Email --}}

                <flux:input
                    label="ایمیل"
                    type="email"
                    placeholder="admin@example.com"
                    wire:model="email"
                />

                @error('email')
                <div class="text-sm text-red-500">
                    {{ $message }}
                </div>
                @enderror


                {{-- Mobile --}}

                <flux:input
                    label="شماره موبایل"
                    placeholder="0912..."
                    wire:model="mobile_number"
                />

                @error('mobile_number')
                <div class="text-sm text-red-500">
                    {{ $message }}
                </div>
                @enderror


                {{-- Password --}}

                <flux:input
                    label="{{ $editingId ? 'رمز عبور جدید' : 'رمز عبور' }}"
                    type="password"
                    placeholder="{{ $editingId ? 'در صورت تغییر وارد کنید' : 'حداقل ۸ کاراکتر' }}"
                    wire:model="password"
                />

                @error('password')
                <div class="text-sm text-red-500">
                    {{ $message }}
                </div>
                @enderror


                {{-- Active --}}

                <div class="
                    flex
                    items-center
                    justify-between
                    rounded-xl
                    border
                    border-zinc-200
                    dark:border-zinc-700
                    p-4
                ">

                    <div>

                        <div class="font-semibold">
                            وضعیت مدیر
                        </div>

                        <div class="
                            text-xs
                            text-zinc-500
                            dark:text-zinc-400
                            mt-1
                        ">
                            مدیر غیرفعال اجازه ورود به پنل را ندارد.
                        </div>

                    </div>


                    <input
                        type="checkbox"
                        wire:model="is_active"
                        class="
                            h-5
                            w-5
                            rounded
                            border-zinc-300
                            text-indigo-600
                            focus:ring-indigo-500
                        "
                    >

                </div>

            </div>


            {{-- Buttons --}}

            <div class="flex justify-end gap-2">

                <flux:button
                    wire:click="closeAdminDialog"
                >
                    انصراف
                </flux:button>

                <flux:button
                    variant="primary"
                    wire:click="saveAdmin"
                    wire:loading.attr="disabled"
                >

                    <span wire:loading.remove>
                        {{ $editingId ? 'ذخیره تغییرات' : 'ایجاد مدیر' }}
                    </span>

                    <span wire:loading>
                        در حال ذخیره...
                    </span>

                </flux:button>

            </div>

        </div>

    </flux:modal>


    {{-- ========================================================= --}}
    {{-- Permissions Dialog --}}
    {{-- ========================================================= --}}

    <flux:modal
        wire:model="showPermissionDialog"
        class="md:w-[600px]"
    >

        <div class="space-y-6">

            <div>

                <flux:heading size="lg">
                    سطح دسترسی مدیر
                </flux:heading>

                <flux:text class="mt-1">
                    دسترسی‌های مورد نظر این مدیر را انتخاب کنید.
                </flux:text>

            </div>


            {{-- Permissions --}}

            <div class="
                max-h-[450px]
                overflow-y-auto
                space-y-2
                pr-1
            ">

                @forelse($permissions as $permission)

                    <label
                        wire:key="permission-{{ $permission['id'] }}"
                        class="
                            flex
                            items-center
                            gap-4
                            cursor-pointer
                            rounded-xl
                            border
                            border-zinc-200
                            dark:border-zinc-700
                            bg-white
                            dark:bg-zinc-900
                            px-4
                            py-3
                            transition
                            hover:bg-zinc-50
                            dark:hover:bg-zinc-800
                        "
                    >

                        <input
                            type="checkbox"
                            value="{{ $permission['id'] }}"
                            wire:model="selectedPermissions"
                            class="
                                h-5
                                w-5
                                rounded
                                border-zinc-300
                                text-indigo-600
                                focus:ring-indigo-500
                            "
                        >


                        <div class="flex-1">

                            <div class="font-medium">
                                {{ $permission['caption'] }}
                            </div>

                            @if(!empty($permission['name']))
                                <div class="
                                    mt-0.5
                                    text-xs
                                    text-zinc-500
                                    dark:text-zinc-400
                                ">
                                    {{ $permission['name'] }}
                                </div>
                            @endif

                        </div>

                    </label>

                @empty

                    <div class="
                        rounded-xl
                        border
                        border-dashed
                        border-zinc-300
                        dark:border-zinc-700
                        py-10
                        text-center
                        text-sm
                        text-zinc-500
                    ">
                        هیچ دسترسی‌ای تعریف نشده است.
                    </div>

                @endforelse

            </div>


            {{-- Buttons --}}

            <div class="flex justify-end gap-2">

                <flux:button
                    wire:click="closePermissionDialog"
                >
                    انصراف
                </flux:button>

                <flux:button
                    variant="primary"
                    wire:click="savePermissions"
                    wire:loading.attr="disabled"
                >

                    <span wire:loading.remove>
                        ذخیره دسترسی‌ها
                    </span>

                    <span wire:loading>
                        در حال ذخیره...
                    </span>

                </flux:button>

            </div>

        </div>

    </flux:modal>

</div>
