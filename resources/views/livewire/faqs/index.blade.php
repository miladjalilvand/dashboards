<div>
    <h1>عنوان</h1>
    <p>متن</p>
    <flux:link as="button" 
     wire:navigate
     >
        + faq
    </flux:link>
    <div x-data="{ open: false }" class="border rounded-lg">
    <button @click="open = !open"
        class="w-full flex justify-between p-4 font-semibold">
        سوال؟
        <span x-text="open ? '-' : '+'"></span>
    </button>

    <div x-show="open" x-collapse class="p-4 text-gray-600">
        جواب اینجاست
    </div>
    <div x-data="{ tab: 1 }">
    <div class="flex border-b">
        <button @click="tab = 1" :class="tab===1 && 'border-b-2 border-blue-600'">دسته ۱</button>
        <button @click="tab = 2" :class="tab===2 && 'border-b-2 border-blue-600'">دسته ۲</button>
    </div>

    <div x-show="tab === 1">محتوای دسته ۱</div>
    <div x-show="tab === 2">محتوای دسته ۲</div>
</div>
</div>

</div>