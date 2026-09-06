<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class ZarinPalService
{
    private string $baseUrl =
        'https://payment.zarinpal.com/pg/v4/payment';




    public function __construct()
    {
        $this->merchantId =
            config('services.zarinpal.merchant_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Request
    |--------------------------------------------------------------------------
    */

    public function request(
        int $amount,
        string $callbackUrl,
        string $description,
        ?string $mobile = null,
        ?string $email = null,
        ?string $key_pass,
    ): array {

        try {

            $metadata = [];

            if ($mobile) {
                $metadata['mobile'] = $mobile;
            }

            if ($email) {
                $metadata['email'] = $email;
            }


            /*
            |--------------------------------------------------------------------------
            | تومان → ریال
            |--------------------------------------------------------------------------
            */

            $rialAmount = $amount * 10;


            $response = Http::timeout(15)
                ->acceptJson()
                ->post(
                    $this->baseUrl . '/request.json',
                    [

                        'merchant_id' =>
                            $key_pass,

                        'amount' =>
                            $rialAmount,

                        'callback_url' =>
                            $callbackUrl,

                        'description' =>
                            $description,

                        'metadata' =>
                            $metadata,
                    ]
                );


            if (!$response->successful()) {

                return [
                    'success' => false,

                    'message' =>
                        'ارتباط با درگاه پرداخت برقرار نشد.',

                    'code' => null,

                    'authority' => null,

                    'response' =>
                        $response->json(),

                    'http_status' =>
                        $response->status(),
                ];
            }


            $data = $response->json();

            $code =
                $data['data']['code'] ?? null;


            if ($code != 100) {

                return [
                    'success' => false,

                    'message' =>
                        $data['data']['message']
                        ?? 'خطا در ایجاد تراکنش.',

                    'code' =>
                        $code,

                    'authority' => null,

                    'response' =>
                        $data,

                    'http_status' =>
                        $response->status(),
                ];
            }


            return [
                'success' => true,

                'message' =>
                    $data['data']['message']
                    ?? 'Success',

                'code' =>
                    $code,

                'authority' =>
                    $data['data']['authority'],

                'response' =>
                    $data,

                'http_status' =>
                    $response->status(),
            ];


        } catch (Throwable $e) {

            report($e);

            return [

                'success' => false,

                'message' =>
                    'خطا در ارتباط با درگاه پرداخت.',

                'code' => null,

                'authority' => null,

                'response' => null,

                'http_status' => 0,
            ];
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Verify
    |--------------------------------------------------------------------------
    */

    public function verify(
        string $authority,
        int $amount  ,
        string $key_pass,
    ): array {

        try {

            $response = Http::timeout(15)
                ->acceptJson()
                ->post(
                    $this->baseUrl . '/verify.json',
                    [

                        'merchant_id' =>
                            $key_pass ,

                        'amount' =>
                            $amount ,

                        'authority' =>
                            $authority,
                    ]
                );


            if (!$response->successful()) {

                return [

                    'success' => false,

                    'message' =>
                        'ارتباط با درگاه برای تایید پرداخت برقرار نشد.',

                    'code' => null,

                    'ref_id' => null,

                    'response' =>
                        $response->json(),

                ];
            }


            $data = $response->json();

            $code =
                $data['data']['code'] ?? null;


            /*
            |--------------------------------------------------------------------------
            | 100 = موفق
            | 101 = قبلاً تایید شده
            |--------------------------------------------------------------------------
            */

            if (!in_array($code, [100, 101])) {

                return [

                    'success' => false,

                    'message' =>
                        $data['data']['message']
                        ?? 'پرداخت تایید نشد.',

                    'code' =>
                        $code,

                    'ref_id' => null,

                    'response' =>
                        $data,
                ];
            }


            return [

                'success' => true,

                'message' =>
                    $data['data']['message']
                    ?? 'پرداخت با موفقیت تایید شد.',

                'code' =>
                    $code,

                'ref_id' =>
                    $data['data']['ref_id']
                    ?? null,

                'response' =>
                    $data,
            ];


        } catch (Throwable $e) {

            report($e);

            return [

                'success' => false,

                'message' =>
                    'خطا در ارتباط با درگاه پرداخت.',

                'code' => null,

                'ref_id' => null,

                'response' => null,
            ];
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Payment URL
    |--------------------------------------------------------------------------
    */

    public function paymentUrl(
        string $authority
    ): string {

        return
            'https://payment.zarinpal.com/pg/StartPay/'
            . $authority;
    }
}
