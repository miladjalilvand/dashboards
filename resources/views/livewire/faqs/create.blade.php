<div class="max-w-2xl mx-auto mt-10">
    <flux:card class="p-6 space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-xl font-bold">ایجاد سوال متداول</h1>
            <p class="text-sm text-gray-500">
                لطفاً اطلاعات مربوط به سوال و پاسخ را وارد کنید
            </p>
        </div>

        {{-- Form --}}
        <form class="space-y-5" wire:submit="store">

            {{-- Select category --}}
            <flux:select
                label="دسته‌بندی"
                placeholder="انتخاب دسته"
                wire:model="category_id"
                :error="$errors->first('category_id')"
            >
            <flux:select.option value="">
                       
                    </flux:select.option>
                @forelse($categories ?? [] as $category)
                    <flux:select.option value="{{ $category->id }}">
                        {{ $category->id }}
                    </flux:select.option>
                @empty
                    <flux:select.option disabled value="">
                        هیچ دسته‌ای یافت نشد
                    </flux:select.option>
                @endforelse
            </flux:select>

            {{-- Title --}}
            <flux:input
                label="عنوان سوال"
                placeholder="مثلاً: چطور ثبت‌نام کنم؟"
                type="text"
                wire:model="title"
                :error="$errors->first('title')"
            />

            {{-- Content --}}
            <flux:textarea
                label="پاسخ سوال"
                placeholder="پاسخ کامل سوال را بنویسید..."
                rows="4"
                wire:model="content"
                :error="$errors->first('content')"
            />

            {{-- Actions --}}
            <div class="flex justify-between items-center pt-4">
                <flux:link href="{{ route('menu_type1') }}" variant="ghost" wire:navigate>
                    بازگشت
                </flux:link>

                <flux:button type="submit" variant="primary">
                    ذخیره سوال
                </flux:button>
            </div>
        </form>

    </flux:card>
</div>