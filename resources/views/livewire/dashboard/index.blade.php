<div class="flex flex-col w-full gap-6" dir="rtl">

    @foreach($dashboards_list as $dashboard)

        @php
            $price = $dashboard['per_of_month'];
            $discount = $dashboard['percentage'];
            $finalPrice = $price - (($price * $discount) / 100);
        @endphp

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 flex flex-col">

            {{-- Header --}}
            <div class="flex items-start justify-between">

                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        {{ $dashboard['caption'] }}
                    </h2>

                    <span class="text-xs text-gray-500">
                        شناسه #{{ $dashboard['id'] }}
                    </span>
                </div>

                @if($discount > 0)
                    <span class="bg-red-500 text-white text-xs px-3 py-1 rounded-full">
                        {{ $discount }}٪ تخفیف
                    </span>
                @endif

            </div>

            {{-- Description --}}
            <p class="text-gray-600 mt-5 leading-7 flex-1">
                {{ $dashboard['description'] }}
            </p>

            <div class="border-t my-6"></div>

            {{-- Price --}}
            @if($discount > 0)

                <div class="text-gray-400 line-through text-lg">
                    {{ number_format($price) }} تومان
                </div>

                <div class="flex items-end gap-2 mt-2">

                    <span class="text-3xl font-extrabold text-green-600">
                        {{ number_format($finalPrice) }}
                    </span>

                    <span class="text-gray-500 text-sm mb-1">
                        تومان / ماه
                    </span>

                </div>

            @else

                <div class="flex items-end gap-2">

                    <span class="text-3xl font-extrabold text-indigo-600">
                        {{ number_format($price) }}
                    </span>

                    <span class="text-gray-500 text-sm mb-1">
                        تومان / ماه
                    </span>

                </div>

            @endif

            {{-- Features --}}
            <div class="mt-6 space-y-2 text-sm text-gray-600">

{{--                <div class="flex items-center gap-2">--}}
{{--                    ✅ لایسنس دائمی--}}
{{--                </div>--}}

                <div class="flex items-center gap-2">
                    ✅ بروزرسانی رایگان
                </div>

                <div class="flex items-center gap-2">
                    ✅ پشتیبانی فنی
                </div>

            </div>

            {{-- Button --}}
            <div class="mt-6">
                <button
                    class="w-full bg-indigo-600 hover:bg-indigo-700 transition rounded-xl py-3 text-white font-semibold">
                    خرید اشتراک
                </button>
{{--              rem !      --}}

                @if($this->is_paid($dashboard['id']))


                    <a href="{{route('dashboard-1')}}">
                    <button
                        class="cursor-pointer mt-1 w-full bg-green-600 hover:bg-green-700 transition rounded-xl py-3 text-white font-semibold">
                        ورود به پنل
                    </button>
                    </a>
                @endif

            </div>

        </div>

    @endforeach

</div>
