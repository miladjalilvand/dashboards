<div class="text-zinc-900 dark:text-zinc-100">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="flex items-center justify-between mb-6">

        <div>

            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">
                درباره ما
            </h2>

            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                مدیریت متن و تصاویر بخش درباره ما
            </p>

        </div>


        <flux:button
            variant="primary"
            wire:click="openDialog"
        >
            ویرایش درباره ما
        </flux:button>

    </div>


    {{-- ========================================================= --}}
    {{-- About Us Preview --}}
    {{-- ========================================================= --}}

    <div
        class="rounded-xl border
               border-zinc-200 dark:border-zinc-700
               bg-white dark:bg-zinc-900
               overflow-hidden"
    >

        {{-- Text --}}

        <div class="p-6">

            <h3 class="font-bold text-lg mb-3">
                متن درباره ما
            </h3>


            @if($aboutus)

                <div
                    class="text-sm leading-7
                           text-zinc-600 dark:text-zinc-300
                           whitespace-pre-line"
                >
                    {{ $aboutus }}
                </div>

            @else

                <div class="text-sm text-zinc-500 dark:text-zinc-400">
                    متنی برای درباره ما ثبت نشده است.
                </div>

            @endif

        </div>


        {{-- ===================================================== --}}
        {{-- Images --}}
        {{-- ===================================================== --}}

        @if(count($images))

            <div
                class="border-t
                       border-zinc-200 dark:border-zinc-700
                       p-6"
            >

                <h3 class="font-bold text-lg mb-4">
                    تصاویر
                </h3>


                <div
                    class="grid
                           grid-cols-2
                           md:grid-cols-3
                           lg:grid-cols-4
                           gap-4"
                >

                    @foreach($images as $index => $image)

                        <div
                            wire:key="aboutus-image-{{ $index }}"
                            class="relative group"
                        >

                            {{-- Image --}}

                            <button
                                type="button"
                                wire:click="openImage({{ $index }})"
                                class="block w-full"
                            >
                                <img
                                    src="{{ asset('storage/' . $image) }}"
                                    class="w-full h-40
                       object-cover
                       rounded-xl
                       cursor-pointer
                       transition
                       duration-200
                       hover:scale-[1.02]"
                                    alt="تصویر درباره ما"
                                >
                            </button>


                            {{-- Delete --}}

                            <button
                                type="button"
                                wire:click="deleteImage({{ $index }})"
                                wire:confirm="آیا از حذف این تصویر مطمئن هستید؟"
                                class="absolute
                   top-2 right-2
                   opacity-100
                   md:opacity-0
                   md:group-hover:opacity-100
                   transition
                   bg-red-600
                   hover:bg-red-700
                   text-white
                   rounded-lg
                   px-3 py-2
                   text-xs
                   min-w-12
                   min-h-10
                   flex
                   items-center
                   justify-center"
                            >
                                حذف
                            </button>

                        </div>

                    @endforeach

                </div>

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- Dialog --}}
    {{-- ========================================================= --}}

    <flux:modal
        wire:model="showDialog"
        class="md:w-[650px]"
    >

        <div class="space-y-6">


            {{-- ================================================= --}}
            {{-- Header --}}
            {{-- ================================================= --}}

            <div>

                <flux:heading size="lg">
                    ویرایش درباره ما
                </flux:heading>

                <flux:text class="mt-1">
                    متن و تصاویر بخش درباره ما را مدیریت کنید.
                </flux:text>

            </div>


            {{-- ================================================= --}}
            {{-- Form --}}
            {{-- ================================================= --}}

            <div class="space-y-6">


                {{-- ================================================= --}}
                {{-- About Us --}}
                {{-- ================================================= --}}

                <flux:textarea
                    label="متن درباره ما"
                    placeholder="متن درباره ما را وارد کنید..."
                    wire:model="aboutus"
                    rows="8"
                />

                @error('aboutus')

                <div class="text-sm text-red-500">
                    {{ $message }}
                </div>

                @enderror


                {{-- ================================================= --}}
                {{-- Existing Images --}}
                {{-- ================================================= --}}

                @if(count($images))

                    <div>

                        <flux:label>
                            تصاویر فعلی
                        </flux:label>


                        <div
                            class="grid
                                   grid-cols-2
                                   md:grid-cols-3
                                   gap-3
                                   mt-3"
                        >

                            @foreach($images as $index => $image)

                                <div
                                    wire:key="current-image-{{ $index }}"
                                    class="relative group"
                                >

                                    {{-- Image --}}

                                    <button
                                        type="button"
                                        wire:click="openImage({{ $index }})"
                                        class="block w-full"
                                    >

                                        <img
                                            src="{{ asset('storage/' . $image) }}"
                                            alt="تصویر درباره ما"
                                            class="w-full
                       h-32
                       object-cover
                       rounded-lg
                       cursor-pointer
                       transition
                       duration-200
                       hover:scale-[1.02]"
                                        >

                                    </button>


                                    {{-- Delete --}}

                                    <button
                                        type="button"
                                        wire:click="deleteImage({{ $index }})"
                                        wire:confirm="آیا از حذف این تصویر مطمئن هستید؟"
                                        class="absolute
                   top-2
                   right-2
                   opacity-100
                   md:opacity-0
                   md:group-hover:opacity-100
                   transition
                   bg-red-600
                   hover:bg-red-700
                   text-white
                   rounded-lg
                   px-3
                   py-2
                   text-xs
                   min-w-12
                   min-h-10
                   flex
                   items-center
                   justify-center"
                                    >
                                        حذف
                                    </button>

                                </div>

                            @endforeach
                        </div>

                    </div>

                @endif


                {{-- ================================================= --}}
                {{-- Upload New Images --}}
                {{-- ================================================= --}}

                <div>

                    <flux:label>
                        افزودن تصاویر
                    </flux:label>


                    <input
                        type="file"
                        wire:model="newImages"
                        multiple
                        accept="image/jpeg,image/png,image/webp"
                        class="mt-2 block w-full
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
                               dark:hover:file:bg-zinc-700"
                    />


                    @error('newImages')
                    <div class="text-sm text-red-500 mt-1">
                        {{ $message }}
                    </div>
                    @enderror


                    @error('newImages.*')
                    <div class="text-sm text-red-500 mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- New Images Preview --}}
                {{-- ================================================= --}}

                @if(count($newImages))

                    <div>

                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-3">
                            تصاویر انتخاب شده:
                        </p>


                        <div
                            class="grid
                                   grid-cols-2
                                   md:grid-cols-3
                                   gap-3"
                        >

                            @foreach($newImages as $index => $image)

                                <div
                                    wire:key="new-image-{{ $index }}"
                                >

                                    <img
                                        src="{{ $image->temporaryUrl() }}"
                                        class="w-full
                                               h-32
                                               object-cover
                                               rounded-lg"
                                    >

                                </div>

                            @endforeach

                        </div>

                    </div>

                @endif

            </div>


            {{-- ================================================= --}}
            {{-- Buttons --}}
            {{-- ================================================= --}}

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
    {{-- ========================================================= --}}
    {{-- Image Dialog --}}
    {{-- ========================================================= --}}

    <flux:modal
        wire:model="showImageDialog"
        class="md:w-[900px]"
    >

        <div class="relative">

            {{-- Close --}}
            <button
                type="button"
                wire:click="closeImage"
                class="absolute
                   top-2 right-2
                   z-10
                   w-10 h-10
                   rounded-full
                   flex items-center justify-center
                   bg-black/60
                   text-white
                   hover:bg-black/80
                   transition"
            >
                ✕
            </button>


            {{-- Image --}}
            @if($selectedImage)

                <div
                    class="flex
                       items-center
                       justify-center
                       min-h-[300px]
                       max-h-[80vh]"
                >

                    <img
                        src="{{ asset('storage/' . $selectedImage) }}"
                        class="max-w-full
                           max-h-[75vh]
                           object-contain
                           rounded-xl"
                        alt="تصویر درباره ما"
                    >

                </div>

            @endif

        </div>

    </flux:modal>
</div>
