<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService2
{
    private string $baseUrl = 'https://api.sms.ir/v1';

    public function sendOtp(
        string $mobile,
        string $code
    ): array {

        $response = Http::asJson()
            ->timeout(15)
            ->withHeaders([
                'X-API-KEY' =>  'vRU3dXKNwOZAKD5RT6WnPfdakfNJT1ThbZBzUauvcUNlO4OIYuYJ9bfz2xJVEAj0',
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post(
                $this->baseUrl . '/send/verify',
                [
                    'mobile' => $mobile,
//*************************************** CREATE TEMP SMS.IR
                    'templateId' => 12345,

                    'parameters' => [
                        [
                            'name' => 'PARAMETER1',
                            'value' => $code,
                        ],
                    ],
                ]
            );

        dd($response);
        $data = $response->json();

        return [
            'success' => ($data['status'] ?? 0) == 1,

            'status' => $data['status'] ?? null,

            'message' => $data['message'] ?? 'خطا در ارسال پیامک',

            'messageId' => $data['data']['messageId'] ?? null,

            'cost' => $data['data']['cost'] ?? null,

            'response' => $data,

            'http_status' => $response->status(),
        ];
    }
}
