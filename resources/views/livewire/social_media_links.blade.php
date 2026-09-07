<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h2 class="text-xl font-bold text-zinc-900 dark:text-white">
            شبکه‌های اجتماعی
        </h2>

        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
            لینک شبکه‌های اجتماعی هر شعبه را مدیریت کنید.
        </p>
    </div>


    {{-- پیام موفقیت --}}
    @if (session()->has('success'))
        <div
            class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700
                   dark:border-green-900/50 dark:bg-green-950/30 dark:text-green-400"
        >
            {{ session('success') }}
        </div>
    @endif


    {{-- فرم --}}
    <div
        class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm
               dark:border-zinc-800 dark:bg-zinc-900"
    >

        <div class="mb-5">
            <h3 class="font-semibold text-zinc-900 dark:text-white">
                افزودن / ویرایش لینک
            </h3>

            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                شعبه و شبکه اجتماعی را انتخاب کرده و لینک را وارد کنید.
            </p>
        </div>


        <form wire:submit="saveData" class="space-y-5">

            {{-- شعبه --}}
            <flux:select
                label="شعبه"
                placeholder="انتخاب شعبه"
                wire:model.live="branch_id"
                :error="$errors->first('branch_id')"
            >

                @foreach($branches as $branch)

                    <flux:select.option value="{{ $branch->id }}">
                        {{ $branch->caption }}
                    </flux:select.option>

                @endforeach

            </flux:select>


            {{-- شبکه اجتماعی --}}
            <flux:select
                label="شبکه اجتماعی"
                placeholder="انتخاب شبکه اجتماعی"
                wire:model="caption"
                :error="$errors->first('caption')"
            >

                <flux:select.option value="">
                    انتخاب کنید
                </flux:select.option>

                @foreach($socialMediaListCaption as $socialMedia)

                    <flux:select.option value="{{ $socialMedia }}">
                        {{ ucfirst($socialMedia) }}
                    </flux:select.option>

                @endforeach

            </flux:select>


            {{-- لینک --}}
            <flux:field>

                <flux:label>
                    آدرس لینک
                </flux:label>

                <flux:input
                    type="url"
                    dir="ltr"
                    placeholder="https://instagram.com/example"
                    wire:model="link"
                />

                <flux:error name="link" />

            </flux:field>


            {{-- دکمه --}}
            <div class="flex justify-end">

                <flux:button
                    type="submit"
                    variant="primary"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>
                        ذخیره
                    </span>

                    <span wire:loading>
                        در حال ذخیره...
                    </span>
                </flux:button>

            </div>

        </form>

    </div>


    {{-- لینک‌های ثبت شده --}}
    @if(count($savedLinks))

        <div
            class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm
                   dark:border-zinc-800 dark:bg-zinc-900"
        >

            <div class="mb-5">

                <h3 class="font-semibold text-zinc-900 dark:text-white">
                    لینک‌های ثبت شده
                </h3>

                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    لینک‌های شبکه اجتماعی شعبه انتخاب شده
                </p>

            </div>


            <div class="grid gap-3 sm:grid-cols-2">

                @foreach($savedLinks as $item)

                    <div
                        class="flex items-center justify-between gap-4 rounded-xl
                               border border-zinc-200 p-4
                               dark:border-zinc-700"
                    >

                        <div class="min-w-0">

                            <div class="font-semibold text-zinc-900 dark:text-white">
                                {{ ucfirst($item['caption']) }}
                            </div>

                            <a
                                href="{{ $item['link'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                dir="ltr"
                                class="mt-1 block truncate text-sm text-blue-600 hover:underline
                                       dark:text-blue-400"
                            >
                                {{ $item['link'] }}
                            </a>

                        </div>


                        <flux:button
                            variant="danger"
                            size="sm"
                            wire:click="deleteLink('{{ $item['caption'] }}')"
                            wire:confirm="آیا از حذف این لینک مطمئن هستید؟"
                        >
                            حذف
                        </flux:button>

                    </div>

                @endforeach

            </div>

        </div>

    @else

        <div
            class="rounded-2xl border border-dashed border-zinc-300 p-8 text-center
                   dark:border-zinc-700"
        >

            <div class="text-zinc-500 dark:text-zinc-400">
                برای این شعبه هنوز لینک شبکه اجتماعی ثبت نشده است.
            </div>

        </div>

    @endif

</div>
