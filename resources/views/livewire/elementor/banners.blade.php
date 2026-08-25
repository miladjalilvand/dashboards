<div class="text-zinc-900 dark:text-zinc-100">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">

        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">
                بنرها
            </h2>

            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                مدیریت بنرهای صفحه درباره ما
            </p>
        </div>

        <flux:button
            variant="primary"
            wire:click="openDialog"
        >
            افزودن بنر
        </flux:button>

    </div>


    {{-- Banner List --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        @forelse($banners as $index => $banner)

            <div
                wire:key="banner-{{ $index }}"
                class="overflow-hidden rounded-xl
                       border border-zinc-200 dark:border-zinc-700
                       bg-white dark:bg-zinc-900
                       shadow-sm"
            >

                {{-- Image --}}
                @if(!empty($banner['image']))

                    <img
                        src="{{ str_starts_with($banner['image'], 'storage/')
                            ? asset($banner['image'])
                            : $banner['image'] }}"
                        class="w-full h-48 object-cover"
                        alt="{{ $banner['title'] }}"
                    >

                @else

                    <div
                        class="w-full h-48
                               bg-zinc-100 dark:bg-zinc-800
                               text-zinc-500 dark:text-zinc-400
                               flex items-center justify-center"
                    >
                        بدون تصویر
                    </div>

                @endif


                {{-- Content --}}
                <div class="p-4">

                    <h3 class="font-bold text-zinc-900 dark:text-white">
                        {{ $banner['title'] }}
                    </h3>

                    <p
                        class="text-sm text-zinc-500 dark:text-zinc-400
                               mt-2 line-clamp-2"
                    >
                        {{ $banner['caption'] }}
                    </p>


                    {{-- Actions --}}
                    <div class="flex gap-2 mt-4">

                        <flux:button
                            size="sm"
                            wire:click="openDialog({{ $index }})"
                        >
                            ویرایش
                        </flux:button>

                        <flux:button
                            size="sm"
                            variant="danger"
                            wire:click="delete({{ $index }})"
                            wire:confirm="آیا از حذف این بنر مطمئن هستید؟"
                        >
                            حذف
                        </flux:button>

                    </div>

                </div>

            </div>

        @empty

            <div
                class="col-span-full
                       text-center py-12
                       text-zinc-500 dark:text-zinc-400"
            >
                هنوز بنری اضافه نشده است.
            </div>

        @endforelse

    </div>


    {{-- Dialog --}}
    <flux:modal
        wire:model="showDialog"
        class="md:w-[600px]"
    >

        <div class="space-y-6">

            {{-- Dialog Header --}}
            <div>

                <flux:heading size="lg">
                    {{ $editingIndex !== null ? 'ویرایش بنر' : 'افزودن بنر' }}
                </flux:heading>

                <flux:text class="mt-1">
                    اطلاعات بنر را وارد کنید.
                </flux:text>

            </div>


            {{-- Form --}}
            <div class="space-y-5">

                {{-- Title --}}
                <flux:input
                    label="عنوان"
                    placeholder="عنوان بنر"
                    wire:model="title"
                />

                @error('title')
                <div class="text-sm text-red-500 dark:text-red-400">
                    {{ $message }}
                </div>
                @enderror


                {{-- Caption --}}
                <flux:textarea
                    label="کپشن"
                    placeholder="توضیحات بنر"
                    wire:model="caption"
                    rows="5"
                />

                @error('caption')
                <div class="text-sm text-red-500 dark:text-red-400">
                    {{ $message }}
                </div>
                @enderror


                {{-- Image --}}
                <div>

                    <flux:label>
                        تصویر
                    </flux:label>

                    <input
                        type="file"
                        wire:model="image"
                        accept="image/jpeg,image/png,image/webp"
                        class="mt-2 block w-full
                               text-sm
                               text-zinc-600 dark:text-zinc-300
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
                               dark:hover:file:bg-zinc-700"
                    >

                    @error('image')
                    <div class="text-sm text-red-500 dark:text-red-400 mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>


                {{-- New Image Preview --}}
                @if($image)

                    <div>

                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-2">
                            پیش‌نمایش:
                        </p>

                        <img
                            src="{{ $image->temporaryUrl() }}"
                            class="w-full h-48 object-cover rounded-xl"
                        >

                    </div>

                @elseif(
                    $editingIndex !== null &&
                    isset($banners[$editingIndex]['image']) &&
                    $banners[$editingIndex]['image']
                )

                    <div>

                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-2">
                            تصویر فعلی:
                        </p>

                        <img
                            src="{{ str_starts_with($banners[$editingIndex]['image'], 'storage/')
                                ? asset($banners[$editingIndex]['image'])
                                : $banners[$editingIndex]['image'] }}"
                            class="w-full h-48 object-cover rounded-xl"
                            alt="{{ $banners[$editingIndex]['title'] }}"
                        >

                    </div>

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
                        ذخیره
                    </span>

                    <span wire:loading>
                        در حال ذخیره...
                    </span>
                </flux:button>

            </div>

        </div>

    </flux:modal>

</div>
