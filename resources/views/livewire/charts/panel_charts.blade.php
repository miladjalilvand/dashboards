<div class="space-y-6">
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

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800
                    bg-white dark:bg-zinc-900 p-5">

            <div class="text-sm text-zinc-500 dark:text-zinc-400">
                تعداد نوبت‌ها
            </div>

            <div class="mt-2 text-3xl font-bold">
                {{ $totalReservations }}
            </div>

        </div>


        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800
                    bg-white dark:bg-zinc-900 p-5">

            <div class="text-sm text-zinc-500 dark:text-zinc-400">
                درآمد
            </div>

            <div class="mt-2 text-3xl font-bold">
                {{ number_format($totalIncome) }}
                <span class="text-sm">تومان</span>
            </div>

        </div>
<div class="rounded-2xl border border-zinc-200 dark:border-zinc-800
                    bg-white dark:bg-zinc-900 p-5">

            <div class="text-sm text-zinc-500 dark:text-zinc-400">
                تخفیف
            </div>

            <div class="mt-2 text-3xl font-bold">
                {{ number_format($totalDis) }}
                <span class="text-sm">تومان</span>
            </div>

        </div>


        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800
                    bg-white dark:bg-zinc-900 p-5">

            <div class="text-sm text-zinc-500 dark:text-zinc-400">
                مشتریان
            </div>

            <div class="mt-2 text-3xl font-bold">
                {{ $totalCustomers }}
            </div>

        </div>

    </div>


    {{-- Chart --}}

    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800
                bg-white dark:bg-zinc-900 p-5">

        <div class="mb-5">

            <h2 class="text-lg font-bold">
                گزارش نوبت‌ها
            </h2>

            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                تعداد نوبت‌های ۷ روز اخیر
            </p>

        </div>

        <div
            id="reservation-chart"
            wire:ignore
            class="w-full h-[350px]"
        ></div>

    </div>

</div>
<script>
    document.addEventListener('livewire:init', () => {

        const initialData = @js($chartData);

        renderReservationChart(initialData);

        Livewire.on('reservation-chart-updated', (event) => {

            renderReservationChart(event.chartData);

        });

    });

    document.addEventListener('livewire:navigated', () => {

        const chartElement = document.getElementById('reservation-chart');

        if (!chartElement) {
            return;
        }

        renderReservationChart(@js($chartData));

    });
</script>
