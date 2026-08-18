<div>
<flux:select
    label="انتخاب شعبه"
    wire:model="current_branch_id"
    :error="$errors->first('current_branch_id')"
    wire:change="onBranchChange"
>
    @forelse($branches ?? [] as $branch)
        <flux:select.option value="{{ $branch->id }}">
            {{ $branch->caption }}
        </flux:select.option>
    @empty
        <flux:select.option disabled value="">
            هیچ شعبه‌ای یافت نشد
        </flux:select.option>
    @endforelse
</flux:select>

<!--
<input type="time" id="ab"/>
<input type="date"  id="ab"/> -->


    <br />

        {{-- دکمه ایجاد مشتری --}}
        <div>
            <flux:button
                variant="primary"
                icon="plus"
                wire:click="openCustomerModal"
            >
                ایجاد مشتری جدید
            </flux:button>
        </div>
    <br />

    <div class="space-y-6">

        {{-- لیست مشتری‌های شعبه --}}
        <div class="space-y-2 p-3">

            @foreach($branch_customers ??[] as $customer)
                <div class="flex items-center justify-between rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-gray-900">

                <span class="font-medium text-gray-800 dark:text-gray-200">
                    {{ $customer->user->name }}
                </span> <span class="font-medium text-gray-800 dark:text-gray-200">
                    {{ $customer->user->mobile_number }}
                </span>

                </div>
            @endforeach

        </div>
        {{-- Modal ایجاد مشتری --}}
        <flux:modal
            name="create-customer"
            class="md:w-[500px]"
        >
            <div class="space-y-6">

                <div>
                    <flux:heading size="lg">
                        ایجاد مشتری جدید
                    </flux:heading>

                    <flux:text class="mt-2">
                        اطلاعات مشتری جدید را وارد کنید.
                    </flux:text>
                </div>

                <form wire:submit="createCustomer" class="space-y-5">

                    <flux:input
                        wire:model="customer_name"
                        label="نام و نام خانوادگی"
                        placeholder="نام مشتری"
                        required
                    />

                    <flux:input
                        wire:model="customer_mobile"
                        label="شماره موبایل"
                        placeholder="09123456789"
                        type="tel"
                        required
                    />

{{--                    <flux:input--}}
{{--                        wire:model="customer_email"--}}
{{--                        label="ایمیل"--}}
{{--                        placeholder="example@gmail.com"--}}
{{--                        type="email"--}}
{{--                    />--}}

{{--                    <flux:input--}}
{{--                        wire:model="customer_password"--}}
{{--                        label="رمز عبور"--}}
{{--                        type="password"--}}
{{--                        required--}}
{{--                        viewable--}}
{{--                    />--}}

                    <div class="flex justify-end gap-2">

                        <flux:modal.close>
                            <flux:button type="button" variant="ghost">
                                انصراف
                            </flux:button>
                        </flux:modal.close>

                        <flux:button
                            type="submit"
                            variant="primary"
                        >
                            ایجاد مشتری
                        </flux:button>

                    </div>

                </form>

            </div>
        </flux:modal>

    </div>


</div>
