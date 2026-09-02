<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reserve;
use App\Models\ReservePaymentLoad;
use App\Services\ZarinPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class PaymentController extends Controller
{
    public function zarinpalCallback(
        Request $request,
        ZarinPalService $zarinPal
    ) {

        try {

            /*
            |--------------------------------------------------------------------------
            | Get Callback Data
            |--------------------------------------------------------------------------
            */

            $authority =
                $request->query('Authority');

            $status =
                $request->query('Status');


            if (!$authority) {

                return response()->json([

                    'success' => false,

                    'message' =>
                        'Authority ارسال نشده است.',

                ], 400);
            }


            /*
            |--------------------------------------------------------------------------
            | Find Reserve Payment Load
            |--------------------------------------------------------------------------
            */

            $load =
                ReservePaymentLoad::query()
                    ->where(
                        'authority',
                        $authority
                    )
                    ->first();


            if (!$load) {

                return response()->json([

                    'success' => false,

                    'message' =>
                        'اطلاعات رزرو برای این تراکنش پیدا نشد.',

                ], 404);
            }


            /*
            |--------------------------------------------------------------------------
            | Already Processed
            |--------------------------------------------------------------------------
            */

            if ($load->status === 'paid') {

                return response()->json([

                    'success' => true,

                    'message' =>
                        'این پرداخت قبلاً پردازش شده است.',

                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Cancelled By User
            |--------------------------------------------------------------------------
            */

            if ($status !== 'OK') {

                $load->update([

                    'status' =>
                        'cancelled',

                ]);


                return response()->json([

                    'success' => false,

                    'message' =>
                        'پرداخت توسط کاربر لغو شد.',

                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Verify
            |--------------------------------------------------------------------------
            */
            $web_sub = explode('.', parse_url($url, PHP_URL_HOST))[0];
            $key_pass = $panel->where('website' ,$web_sub )->first()->website;
            $verify =
                $zarinPal->verify(

                    authority:
                    $authority,

                    amount:
                    $load->payment_amount,
                    key_pass : $key_pass
                );


            /*
            |--------------------------------------------------------------------------
            | Verify Failed
            |--------------------------------------------------------------------------
            */

            if (!$verify['success']) {

                $load->update([

                    'status' =>
                        'failed',

                    'response' =>
                        $verify['response'],

                ]);


                return response()->json([

                    'success' => false,

                    'message' =>
                        $verify['message'],

                    'code' =>
                        $verify['code'],

                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | SUCCESS
            |--------------------------------------------------------------------------
            */

            $result = DB::transaction(

                function () use (
                    $load,
                    $verify
                ) {


                    /*
                    |--------------------------------------------------------------------------
                    | Create Payment
                    |--------------------------------------------------------------------------
                    */

                    $payment =
                        Payment::create([

                            'amount' =>
                                $load->payment_amount,

                            'authority' =>
                                $load->authority,

                            'status_code' =>
                                $verify['code'],

                            'status' =>
                                'paid',

                            'ref_id' =>
                                $verify['ref_id'],

                            'description' =>
                                $load->description,

                            'response' =>
                                $verify['response'],

                            'paid_at' =>
                                now(),

                            'method' =>
                                'zarinpal',
                        ]);


                    /*
                    |--------------------------------------------------------------------------
                    | Create Reserve
                    |--------------------------------------------------------------------------
                    */

                    $reserve =
                        Reserve::create([

                            'branch_id' =>
                                $load->branch_id,

                            /*
                            |--------------------------------------------------------------------------
                            | وضعیت اولیه رزرو
                            |--------------------------------------------------------------------------
                            |
                            | این مقدار را با status_id واقعی
                            | پروژه خودت جایگزین کن.
                            |
                            */

                            'status_id' =>
                                1,

                            'customer_id' =>
                                $load->customer_id,

                            'start_time' =>
                                $load->start_time,

                            'end_time' =>
                                $load->end_time,

                            'total_cost' =>
                                $load->total_cost,

                            'discount' =>
                                $load->discount,

                            'total_time' =>
                                $load->total_time,

                            'employee_id' =>
                                $load->employee_id,

                            'date' =>
                                $load->date,
                        ]);


                    /*
                    |--------------------------------------------------------------------------
                    | Attach Payment To Reserve
                    |--------------------------------------------------------------------------
                    */

                    $reserve->payments()->attach(
                        $payment->id
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Mark Load As Paid
                    |--------------------------------------------------------------------------
                    */

                    $load->update([

                        'status' =>
                            'paid',

                        'response' =>
                            array_merge(

                                $load->response ?? [],

                                [
                                    'verify' =>
                                        $verify['response']
                                ]

                            ),
                    ]);


                    return [
                        'reserve' =>
                            $reserve,

                        'payment' =>
                            $payment,
                    ];
                }
            );


            /*
            |--------------------------------------------------------------------------
            | Delete Temporary Load
            |--------------------------------------------------------------------------
            */

            $load->delete();


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'success' => true,

                'message' =>
                    'پرداخت با موفقیت انجام شد.',

                'reserve_id' =>
                    $result['reserve']->id,

                'payment_id' =>
                    $result['payment']->id,

                'ref_id' =>
                    $result['payment']->ref_id,

            ]);


        } catch (Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Log Error
            |--------------------------------------------------------------------------
            */

            report($e);


            /*
            |--------------------------------------------------------------------------
            | Don't Show Laravel Exception
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'success' => false,

                'message' =>
                    'خطایی در پردازش پرداخت رخ داد. لطفاً با پشتیبانی تماس بگیرید.',

            ], 500);
        }
    }
}
