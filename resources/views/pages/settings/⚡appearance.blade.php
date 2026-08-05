<?php

use Livewire\Component;

new class extends Component {
    //
}; ?>

<section class="w-full"> @include('partials.settings-heading') <flux:heading class="sr-only">تنظیمات ظاهر</flux:heading> <x-pages::settings.layout heading="ظاهر" subheading="تنظیمات ظاهری حساب کاربری خود را به‌روزرسانی کنید" > <flux:radio.group x-data variant="segmented" x-model="$flux.appearance"> <flux:radio value="light" icon="sun">روشن</flux:radio> <flux:radio value="dark" icon="moon">تاریک</flux:radio> <flux:radio value="system" icon="computer-desktop">سیستم</flux:radio> </flux:radio.group> </x-pages::settings.layout> </section>
