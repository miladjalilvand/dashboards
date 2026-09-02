<?php

namespace App\Services;

use App\Models\ReservePaymentLoad;
use Illuminate\Support\Facades\DB;
use Throwable;

class PaymentService
{
    public function __construct(
        private ZarinPalService $zarinPal
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Start Reserve Payment
    |--------------------------------------------------------------------------
    */

    public function startReservePayment(
        array $data
    ): array {

        try {
dd($data);
            /*
            |--------------------------------------------------------------------------
            | محاسبه مبلغ
            |--------------------------------------------------------------------------
            */

            $totalCost =
                (int) $data['total_cost'];

            $discount =
                (int) ($data['discount'] ?? 0);


            $discountAmount =
                ($totalCost * $discount) / 100;


            $paymentAmount =
                (int) round(
                    $totalCost - $discountAmount
                );


            if ($paymentAmount <= 0) {

                return [

                    'success' => false,

                    'message' =>
                        'مبلغ پرداخت معتبر نیست.',
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | ساخت ReservePaymentLoad
            |--------------------------------------------------------------------------
            */

            $load = ReservePaymentLoad::create([

                'branch_id' =>
                    $data['branch_id'],

                'customer_id' =>
                    $data['customer_id'],

                'employee_id' =>
                    $data['employee_id'],

                'service_id' =>
                    $data['service_id'] ?? null,

                'start_time' =>
                    $data['start_time'],

                'end_time' =>
                    $data['end_time'],

                'total_cost' =>
                    $totalCost,

                'discount' =>
                    $discount,

                'total_time' =>
                    $data['total_time'],

                'date' =>
                    $data['date'],

                'payment_amount' =>
                    $paymentAmount,

                'status' =>
                    'pending',

                'description' =>
                    $data['description']
                    ?? 'پرداخت رزرو',

            ]);


            /*
            |--------------------------------------------------------------------------
            | Callback
            |--------------------------------------------------------------------------
            */

            $callbackUrl =
                route(
                    'payment.zarinpal.callback'
                );


            /*
            |--------------------------------------------------------------------------
            | Request ZarinPal
            |--------------------------------------------------------------------------
            */

            $result =
                $this->zarinPal->request(

                    amount:
                    $paymentAmount,

                    callbackUrl:
                    $callbackUrl,

                    description:
                    'پرداخت رزرو',

                    mobile:
                    $data['mobile'] ?? null,

                    email:
                    $data['email'] ?? null,
                    key_pass : $data['key_pass']
                );


            /*
            |--------------------------------------------------------------------------
            | Request Failed
            |--------------------------------------------------------------------------
            */

            if (!$result['success']) {

                $load->update([

                    'status' =>
                        'failed',

                    'response' =>
                        $result['response'],

                ]);


                return [

                    'success' => false,

                    'message' =>
                        $result['message'],

                    'load' =>
                        $load,
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Save Authority
            |--------------------------------------------------------------------------
            */

            $load->update([

                'authority' =>
                    $result['authority'],

                'response' =>
                    $result['response'],

            ]);


            /*
            |--------------------------------------------------------------------------
            | Return Payment URL
            |--------------------------------------------------------------------------
            */

            return [

                'success' => true,

                'message' =>
                    'درخواست پرداخت با موفقیت ایجاد شد.',

                'authority' =>
                    $result['authority'],

                'payment_url' =>
                    $this->zarinPal->paymentUrl(
                        $result['authority']
                    ),

                'load_id' =>
                    $load->id,
            ];


        } catch (Throwable $e) {

            report($e);

            return [

                'success' => false,

                'message' =>
                    'خطایی در ایجاد درخواست پرداخت رخ داد.',
            ];
        }
    }
}
