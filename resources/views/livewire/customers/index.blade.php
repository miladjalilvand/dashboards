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
    {{-- لیست مشتری‌های شعبه --}} <div class="space-y-2"> @foreach($branch_customers as $customer) <div class="flex items-center justify-between rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-gray-900"> <span class="font-medium text-gray-800 dark:text-gray-200"> {{ $customer->user->name }} </span> </div> @endforeach </div>

</div>
