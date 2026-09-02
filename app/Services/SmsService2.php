<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class SmsService2
{
    private string $baseUrl = 'https://api.sms.ir/v1';

    public function sendOtp(
        string $mobile,
        string $code
    ): array {

        try {

            $response = Http::asJson()
                ->timeout(15)
                ->withHeaders([
                    'X-API-KEY' => 'YOUR_API_KEY',
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post(
                    $this->baseUrl . '/send/verify',
                    [
                        'mobile' => $mobile,

                        'templateId' => 12345,

                        'parameters' => [
                            [
                                'name' => 'PARAMETER1',
                                'value' => $code,
                            ],
                        ],
                    ]
                );

            $data = $response->json();

            return [
                'success' => ($data['status'] ?? 0) == 1,

                'status' => $data['status'] ?? null,

                'message' => $data['message']
                    ?? 'خطا در ارسال پیامک',

                'messageId' => $data['data']['messageId']
                    ?? null,

                'cost' => $data['data']['cost']
                    ?? null,

                'response' => $data,

                'http_status' => $response->status(),
            ];

        } catch (ConnectionException $e) {

            return [
                'success' => false,
                'status' => null,
                'message' => 'ارتباط با سامانه پیامک برقرار نشد.',
                'messageId' => null,
                'cost' => null,
                'response' => null,
                'http_status' => null,
            ];

        } catch (\Throwable $e) {

            return [
                'success' => false,
                'status' => null,
                'message' => 'خطایی در ارسال پیامک رخ داد.',
                'messageId' => null,
                'cost' => null,
                'response' => null,
                'http_status' => null,
            ];
        }
    }
}
