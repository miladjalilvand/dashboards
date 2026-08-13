
<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>راهنمای راه‌اندازی سیستم نوبت‌دهی</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-900">

    {{-- Header --}}
    <header class="w-full border-b bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5">

            <div>
                <h1 class="text-xl font-bold">
                    سیستم نوبت‌دهی
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    راهنمای راه‌اندازی پنل نوبت‌دهی
                </p>
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center gap-3">

                    @auth

                        <a href="{{ url('/dashboards') }}"
                           class="rounded-lg bg-black px-5 py-2.5 text-sm font-medium text-white hover:bg-gray-800">
                            داشبوردها
                        </a>

                    @else

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="rounded-lg bg-black px-5 py-2.5 text-sm font-medium text-white hover:bg-gray-800">
                                ثبت‌نام
                            </a>
                        @endif

                        <a href="{{ route('login') }}"
                           class="rounded-lg px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100">
                            ورود
                        </a>

                    @endauth

                </nav>
            @endif

        </div>
    </header>


    {{-- Main --}}
    <main class="mx-auto max-w-6xl px-6 py-16">

        {{-- Intro --}}
        <div class="mx-auto max-w-2xl text-center">

            <span class="inline-flex rounded-full bg-blue-100 px-4 py-1.5 text-sm font-medium text-blue-700">
                شروع کار با سیستم
            </span>

            <h2 class="mt-5 text-3xl font-bold tracking-tight sm:text-4xl">
                در ۳ مرحله سیستم نوبت‌دهی خودت را راه‌اندازی کن
            </h2>

            <p class="mt-4 text-base leading-7 text-gray-500">
                برای شروع کافی است مراحل زیر را انجام دهید.
                در کمتر از چند دقیقه می‌توانید پنل نوبت‌دهی خود را آماده کنید.
            </p>

        </div>


        {{-- Steps --}}
        <div class="relative mt-16 grid gap-6 md:grid-cols-3">

            {{-- Step 1 --}}
            <div class="relative rounded-2xl border border-gray-200 bg-white p-7 shadow-sm">

                <div class="flex items-center justify-between">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-lg font-bold text-white">
                        ۱
                    </div>

                    <span class="text-sm font-medium text-gray-400">
                        مرحله اول
                    </span>

                </div>

                <h3 class="mt-6 text-xl font-bold">
                    ثبت‌نام و خرید پنل
                </h3>

                <p class="mt-3 leading-7 text-gray-500">
                    ابتدا در سیستم ثبت‌نام کنید و پس از ورود،
                    پنل نوبت‌دهی موردنظر خود را خریداری کنید.
                </p>

                <div class="mt-6 rounded-xl bg-gray-50 p-4">

                    <p class="text-sm font-medium text-gray-700">
                        در این مرحله:
                    </p>

                    <ul class="mt-3 space-y-2 text-sm text-gray-500">
                        <li>✓ ایجاد حساب کاربری</li>
                        <li>✓ انتخاب پنل نوبت‌دهی</li>
                        <li>✓ پرداخت و فعال‌سازی</li>
                    </ul>

                </div>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="mt-6 block rounded-xl bg-blue-600 px-5 py-3 text-center text-sm font-medium text-white transition hover:bg-blue-700">
                        شروع ثبت‌نام
                    </a>
                @endif

            </div>


            {{-- Step 2 --}}
            <div class="relative rounded-2xl border border-gray-200 bg-white p-7 shadow-sm">

                <div class="flex items-center justify-between">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-600 text-lg font-bold text-white">
                        ۲
                    </div>

                    <span class="text-sm font-medium text-gray-400">
                        مرحله دوم
                    </span>

                </div>

                <h3 class="mt-6 text-xl font-bold">
                    تکمیل اطلاعات
                </h3>

                <p class="mt-3 leading-7 text-gray-500">
                    بعد از فعال‌سازی پنل، اطلاعات مجموعه خود را وارد کنید
                    تا سیستم برای دریافت نوبت آماده شود.
                </p>

                <div class="mt-6 rounded-xl bg-gray-50 p-4">

                    <p class="text-sm font-medium text-gray-700">
                        اطلاعات موردنیاز:
                    </p>

                    <ul class="mt-3 space-y-2 text-sm text-gray-500">
                        <li>✓ شعبه‌ها</li>
                        <li>✓ دسته‌بندی خدمات</li>
                        <li>✓ سرویس‌ها</li>
                        <li>✓ کارمندان</li>
                    </ul>

                </div>

                <div class="mt-6 rounded-xl border border-dashed border-gray-300 px-4 py-3 text-center text-sm text-gray-400">
                    اطلاعات را از داخل داشبورد وارد کنید
                </div>

            </div>


            {{-- Step 3 --}}
            <div class="relative rounded-2xl border border-gray-200 bg-white p-7 shadow-sm">

                <div class="flex items-center justify-between">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-600 text-lg font-bold text-white">
                        ۳
                    </div>

                    <span class="text-sm font-medium text-gray-400">
                        مرحله سوم
                    </span>

                </div>

                <h3 class="mt-6 text-xl font-bold">
                    ارسال لینک نوبت‌دهی
                </h3>

                <p class="mt-3 leading-7 text-gray-500">
                    حالا سیستم شما آماده دریافت نوبت است.
                    لینک نوبت‌دهی را برای مشتریان خود ارسال کنید.
                </p>

                <div class="mt-6 rounded-xl bg-gray-50 p-4">

                    <p class="text-sm font-medium text-gray-700">
                        می‌توانید لینک را:
                    </p>

                    <ul class="mt-3 space-y-2 text-sm text-gray-500">
                        <li>✓ در اینستاگرام قرار دهید</li>
                        <li>✓ در واتساپ ارسال کنید</li>
                        <li>✓ برای مشتری پیامک کنید</li>
                        <li>✓ در سایت خود قرار دهید</li>
                    </ul>

                </div>

                <div class="mt-6 rounded-xl bg-green-50 px-4 py-3 text-center text-sm font-medium text-green-700">
                    🎉 سیستم شما آماده دریافت نوبت است
                </div>

            </div>

        </div>


        {{-- Bottom CTA --}}
        <div class="mt-16 rounded-3xl bg-gray-900 px-6 py-10 text-center text-white">

            <h3 class="text-2xl font-bold">
                آماده‌ای شروع کنی؟
            </h3>

            <p class="mx-auto mt-3 max-w-xl text-gray-400">
                همین حالا ثبت‌نام کن و پنل نوبت‌دهی خودت را راه‌اندازی کن.
            </p>

            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="mt-6 inline-block rounded-xl bg-white px-7 py-3 font-medium text-gray-900 transition hover:bg-gray-100">
                    ایجاد حساب و شروع کار
                </a>
            @endif

        </div>

    </main>
    <footer class="mt-16 border-t border-gray-200 bg-white">
    <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-6 py-8 sm:flex-row">

        <div class="text-sm text-gray-500">
            © {{ date('Y') }} تمامی حقوق محفوظ است.
        </div>

        <div class="flex items-center gap-6 text-sm text-gray-500">
            <a href="#" class="transition hover:text-gray-900">
                درباره ما
            </a>

            <a href="#" class="transition hover:text-gray-900">
                تماس با ما
            </a>

            <a href="#" class="transition hover:text-gray-900">
                قوانین و مقررات
            </a>

            <a href="#" class="transition hover:text-gray-900">
                حریم خصوصی
            </a>
        </div>

    </div>
</footer>

</body>
</html>
