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
    <div class="mt-6 space-y-2"> @foreach($branch_payments as $payment) <div class="grid grid-cols-3 items-center gap-4 rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-gray-900"> {{-- User --}} <div class="font-medium text-gray-800 dark:text-gray-200"> {{ $payment->user->name }} </div> {{-- Ref --}} <div class="text-sm text-gray-600 dark:text-gray-400"> {{ $payment->reff }} </div> {{-- Amount --}} <div class="font-semibold text-gray-900 dark:text-white"> {{ number_format($payment->amount) }} </div> </div> @endforeach </div>
</div>
