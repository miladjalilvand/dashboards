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

</div>