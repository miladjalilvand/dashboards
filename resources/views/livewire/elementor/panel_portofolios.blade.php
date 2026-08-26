<div class="text-zinc-900 dark:text-zinc-100">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">

        <div>

            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">
                نمونه‌کارها
            </h2>

            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                نمونه‌کارهای مجموعه را مدیریت کنید.
            </p>

        </div>


        <flux:button
            variant="primary"
            wire:click="openDialog"
        >
            افزودن نمونه‌کار
        </flux:button>

    </div>


    {{-- ========================================================= --}}
    {{-- Portfolio List --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        @forelse($portfolios as $portfolio)

            <div
                wire:key="portfolio-{{ $portfolio->id }}"
                class="
                    overflow-hidden
                    rounded-2xl
                    border border-zinc-200 dark:border-zinc-700
                    bg-white dark:bg-zinc-900
                    shadow-sm
                    transition
                    hover:-translate-y-1
                    hover:shadow-lg
                "
            >

                {{-- Image --}}

                @if($portfolio->image)

                    <div class="relative">

                        <img
                            src="{{ asset('storage/' . ltrim($portfolio->image, '/')) }}"
                            class="
                                w-full
                                h-52
                                object-cover
                            "
                            alt="{{ $portfolio->service?->caption }}"
                        >

                    </div>

                @else

                    <div
                        class="
                            w-full
                            h-52
                            flex
                            items-center
                            justify-center
                            bg-zinc-100
                            dark:bg-zinc-800
                            text-zinc-400
                        "
                    >
                        بدون تصویر
                    </div>

                @endif


                {{-- Content --}}

                <div class="p-5">

                    {{-- Service --}}

                    <h3
                        class="
                            font-bold
                            text-lg
                            text-zinc-900
                            dark:text-white
                        "
                    >
                        {{ $portfolio->service?->caption ?? 'سرویس حذف شده' }}
                    </h3>


                    {{-- Employee --}}

                    <div
                        class="
                            mt-2
                            text-sm
                            text-zinc-500
                            dark:text-zinc-400
                        "
                    >
                        کارمند:
                        <span class="font-medium">
                            {{ $portfolio->employee?->name ?? 'کارمند حذف شده' }}
                        </span>
                    </div>


                    {{-- Caption --}}

                    @if($portfolio->caption)

                        <p
                            class="
                                mt-4
                                text-sm
                                leading-7
                                text-zinc-600
                                dark:text-zinc-300
                                line-clamp-3
                            "
                        >
                            {{ $portfolio->caption }}
                        </p>

                    @endif


                    {{-- Actions --}}

                    <div
                        class="
                            flex
                            gap-2
                            mt-5
                            pt-4
                            border-t
                            border-zinc-200
                            dark:border-zinc-700
                        "
                    >

                        <flux:button
                            size="sm"
                            wire:click="openDialog({{ $portfolio->id }})"
                        >
                            ویرایش
                        </flux:button>


                        <flux:button
                            size="sm"
                            variant="danger"
                            wire:click="delete({{ $portfolio->id }})"
                            wire:confirm="آیا از حذف این نمونه‌کار مطمئن هستید؟"
                        >
                            حذف
                        </flux:button>

                    </div>

                </div>

            </div>

        @empty

            <div
                class="
                    col-span-full
                    rounded-2xl
                    border border-dashed
                    border-zinc-300 dark:border-zinc-700
                    py-16
                    text-center
                "
            >

                <div class="text-4xl mb-3">
                    💼
                </div>

                <p class="font-medium">
                    هنوز نمونه‌کاری ثبت نشده است.
                </p>

                <p
                    class="
                        text-sm
                        text-zinc-500
                        dark:text-zinc-400
                        mt-1
                    "
                >
                    برای شروع یک نمونه‌کار جدید اضافه کنید.
                </p>

            </div>

        @endforelse

    </div>


    {{-- ========================================================= --}}
    {{-- Dialog --}}
    {{-- ========================================================= --}}

    <flux:modal
        wire:model="showDialog"
        class="md:w-[600px]"
    >

        <div class="space-y-6">

            {{-- Header --}}

            <div>

                <flux:heading size="lg">

                    {{ $editingId ? 'ویرایش نمونه‌کار' : 'افزودن نمونه‌کار' }}

                </flux:heading>

                <flux:text class="mt-1">
                    اطلاعات نمونه‌کار را وارد کنید.
                </flux:text>

            </div>


            {{-- Form --}}
            {{-- Branch --}}


            <div class="space-y-5">
                <flux:select
                    label="شعبه"
                    wire:model.live="selected_branch"
                >
                    <option value="">
                        انتخاب شعبه
                    </option>

                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">
                            {{ $branch->caption }}
                        </option>
                    @endforeach
                </flux:select>


                @error('service_id')

                <div class="text-sm text-red-500">
                    {{ $message }}
                </div>

                @enderror

                {{-- Service --}}
@if($selected_branch)

                    <flux:select
                        label="سرویس"
                        wire:model="service_id"
                    >

                        <option value="">
                            انتخاب سرویس
                        </option>

                        @foreach($services as $service)

                            <option value="{{ $service->id }}">
                                {{ $service->caption }}
                            </option>

                        @endforeach

                    </flux:select>


                    @error('service_id')

                    <div class="text-sm text-red-500">
                        {{ $message }}
                    </div>

                    @enderror


                    {{-- Employee --}}

                    <flux:select
                        label="کارمند"
                        wire:model="employee_id"
                    >

                        <option value="">
                            انتخاب کارمند
                        </option>

                        @foreach($employees as $employee)

                            <option value="{{ $employee->id }}">
                                {{ $employee->name }}
                            </option>

                        @endforeach

                    </flux:select>


                    @error('employee_id')

                    <div class="text-sm text-red-500">
                        {{ $message }}
                    </div>

                    @enderror
@endif

                    {{-- Caption --}}

                <flux:textarea
                    label="توضیحات"
                    placeholder="توضیحات نمونه‌کار را وارد کنید..."
                    wire:model="caption"
                    rows="6"
                />

                @error('caption')

                <div class="text-sm text-red-500">
                    {{ $message }}
                </div>

                @enderror


                {{-- Image --}}

                <div>

                    <flux:label>
                        تصویر نمونه‌کار
                    </flux:label>


                    <input
                        type="file"
                        wire:model="image"
                        accept="image/jpeg,image/png,image/webp"
                        class="
                            mt-2
                            block
                            w-full
                            text-sm
                            text-zinc-600
                            dark:text-zinc-300

                            file:mr-4
                            file:rounded-lg
                            file:border-0

                            file:bg-zinc-100
                            dark:file:bg-zinc-800

                            file:px-4
                            file:py-2

                            file:text-sm
                            file:font-medium

                            file:text-zinc-700
                            dark:file:text-zinc-200

                            hover:file:bg-zinc-200
                            dark:hover:file:bg-zinc-700
                        "
                    >


                    @error('image')

                    <div class="text-sm text-red-500 mt-1">
                        {{ $message }}
                    </div>

                    @enderror

                </div>


                {{-- New Image Preview --}}

                @if($image)

                    <div>

                        <p
                            class="
                                text-sm
                                text-zinc-500
                                dark:text-zinc-400
                                mb-2
                            "
                        >
                            پیش‌نمایش تصویر جدید
                        </p>


                        <img
                            src="{{ $image->temporaryUrl() }}"
                            class="
                                w-full
                                h-52
                                object-cover
                                rounded-xl
                            "
                        >

                    </div>

                @elseif($editingId)

                    @php
                        $editingPortfolio = $portfolios->firstWhere('id', $editingId);
                    @endphp

                    @if($editingPortfolio?->image)

                        <div>

                            <p
                                class="
                                    text-sm
                                    text-zinc-500
                                    dark:text-zinc-400
                                    mb-2
                                "
                            >
                                تصویر فعلی
                            </p>


                            <img
                                src="{{ asset('storage/' . ltrim($editingPortfolio->image, '/')) }}"
                                class="
                                    w-full
                                    h-52
                                    object-cover
                                    rounded-xl
                                "
                            >

                        </div>

                    @endif

                @endif

            </div>


            {{-- Buttons --}}

            <div class="flex justify-end gap-2">

                <flux:button
                    wire:click="closeDialog"
                >
                    انصراف
                </flux:button>


                <flux:button
                    variant="primary"
                    wire:click="save"
                    wire:loading.attr="disabled"
                >

                    <span wire:loading.remove>
                        {{ $editingId ? 'ویرایش' : 'ذخیره' }}
                    </span>

                    <span wire:loading>
                        در حال ذخیره...
                    </span>

                </flux:button>

            </div>

        </div>

    </flux:modal>

</div>
