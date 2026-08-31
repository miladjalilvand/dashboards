<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    private string $baseUrl = 'https://api.payamakapi.ir/api/v1';

    private array $errorMessages = [
        0  => 'ارسال با موفقیت انجام شد.',
        1  => 'نام کاربری یا کلمه عبور نامعتبر است.',
        2  => 'کاربر مسدود شده است.',
        3  => 'شماره فرستنده نامعتبر است.',
        4  => 'محدودیت در ارسال روزانه.',
        5  => 'تعداد گیرندگان بیشتر از حد مجاز است.',
        6  => 'خط فرستنده غیرفعال است.',
        7  => 'متن پیامک شامل کلمات فیلترشده است.',
        8  => 'اعتبار کافی نیست.',
        9  => 'سامانه در حال به‌روزرسانی است.',
        10 => 'وب‌سرویس غیرفعال است.',
        12 => 'تعداد پیام‌ها و شماره‌ها باید یکسان باشد.',
        13 => 'حداکثر مجاز در ارسال متناظر ۵۰۰ شماره است.',
        14 => 'کاربر فاقد تعرفه است.',
        15 => 'ارسال تکراری پیام مشابه به شماره مشابه.',
        16 => 'شماره موبایل گیرنده یافت نشد.',
        17 => 'خط OTP برای کاربر یافت نشد.',
        18 => 'با این شماره فقط ارسال تکی مجاز است.',
        19 => 'متن ارسالی با الگوی تعریف‌شده مطابقت ندارد.',
        21 => 'IP شما برای ارسال مجاز نیست.',
        22 => 'کاربر تأیید نشده یا کارت ملی ارسال نشده است.',
    ];

    public function sendOtp(
        string $toNumber,
        string $code
    ): array {
        $response = Http::timeout(15)
            ->withHeaders([
                'X-API-KEY' => env('SMS_API_KEY'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post(
                $this->baseUrl . '/SMS/Send',
                [
                    'From' => '9998883493',

                    'ToNumber' => $toNumber,

                    'PatternId' => '12345',

                    'PatternParameterData' => [
                        'ParameterValue' => $code,
                    ],
                ]
            );

//        dd($response);
        $data = $response->json();

        $resultCode = $data['resultCode'] ?? null;

        return [
            'success' => ($data['Succeeded'] ?? false) === true,
            'resultCode' => $resultCode,

            'message' => $this->errorMessages[$resultCode]
                ?? 'خطای نامشخص در ارسال پیامک.',

            'refId' => $data['refId'] ?? null,
            'response' => $data,
            'http_status' => $response->status(),
        ];
    }
}
