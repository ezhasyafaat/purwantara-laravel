<?php

namespace Ezhasyafaat\PurwantaraPayment;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class Purwantara
{
    private $host;

    public function __construct()
    {
        if (config('purwantara.mode') == 'PRODUCTION') {
            $this->host = 'https://api.purwantara.id/v1/';
        } elseif (config('purwantara.mode') == 'SANDBOX') {
            $this->host = 'https://sandbox-api.purwantara.id/v1/';
        }
    }

    public function create_virtual_account($input = [])
    {
        $parameter = [
            'name' => $input['display_name'],
            'bank' => $input['channel_name'],
            'external_id' => $input['order_id_merchant'],
            'merchant_trx_id' => $input['order_id_merchant'],
            'expected_amount' => $input['amount'],
            'description' => isset($input['description']) ? $input['description'] : null,
            'expired_at' => Carbon::parse($input['expired_at'])->toIso8601String(),
        ];

        try {
            $response = Http::withToken(config('purwantara.token'))
                ->post($this->host . 'virtual-account', $parameter);

            $response = $response->json();

            if ($response['success']) {
                $value = $response['data'];
                $return = [
                    'purwantara_uuid' => $value['uuid'],
                    'order_id_merchant' => $value['external_id'],
                    'display_name' => $value['name'],
                    'channel_name' => $input['channel_name'],
                    'amount' => $value['amount'],
                    'virtual_number' => $value['va_number'],
                    'description' => $value['description'],
                    'expired' => $value['expired_at'],
                    'payment_status' => $value['status'],
                ];

                return $return;
            }

            return ['message' => 'Failed created virtual account'];
        } catch (\Throwable $th) {
            return ['message' => $th->getMessage()];
        }
    }

    public function cancel_virtual_account($input = [])
    {
        try {
            $response = Http::withToken(config('app.token'))
                ->post($this->host . 'virtual-account/cancel/' . $input['purwantara_uuid']);

            $data = $response->json();

            if ($data['success'] == true) {
                $value = $data['data'];
                $return = [
                    'order_id_merchant' => $value['external_id'],
                    'purwantara_uuid' => $value['uuid'],
                    'virtual_number' => $value['va_number'],
                    'message' => $value['message'],
                ];

                return $return;
            }

            return ['message' => 'Failed cancel virtual account'];
        } catch (\Throwable $th) {
            return ['message' => $th->getMessage()];
        }
    }

    public function inquiry_virtual_account($input = [])
    {
        try {
            $response = Http::withToken(config('app.token'))
                ->get($this->host . 'virtual-account/inquiry/' . $input['purwantara_uuid']);

            $reponse = $response->json();

            if ($response['success']) {
                $value = $response['data'];
                $return = [
                    'purwantara_uuid' => $value['uuid'],
                    'order_id_merchant' => $value['external_id'],
                    'display_name' => $value['name'],
                    'channel_name' => $value['bank'],
                    'virtual_number' => $value['va_number'],
                    'amount' => $value['amount'],
                    'description' => $value['description'],
                    'expired' => $value['expired_at'],
                    'payment_status' => $value['status'],
                ];

                return $return;
            }

            return ['message' => 'Failed inquiry virtual account'];
        } catch (\Throwable $th) {
            return ['message' => $th->getMessage()];
        }
    }

    public function create_qris($input = [])
    {
        $parameter = [
            'amount' => $input['amount'],
            'customer_email' => $input['customer_email'],
            'customer_first_name' => $input['customer_first_name'],
            'customer_last_name' => $input['customer_last_name'],
            'customer_phon' => $input['customer_phone'],
            'transaction_description' => isset($input['description']) ? $input['description'] : null,
            'payment_channel' => $input['channel_name'],
            'order_id' => $input['order_id_merchant'],
            'merchant_trx_id' => $input['order_id_merchant'],
            'payment_method' => 'wallet',
        ];

        try {
            $response = Http::withToken(config('purwantara.token'))
                ->post($this->host . 'qris', $parameter);

            $response = $response->json();

            if ($response['success']) {
                $value = $response['data'];
                $return = [
                    'purwantara_uuid' => $value['uuid'],
                    'order_id_merchant' => $value['external_id'],
                    'qris_string' => $value['qr_string'],
                    'qris_url' => $value['qr_url'],
                    'expired' => $value['expired_time'],
                    'payment_status' => $value['status'],
                ];

                return $return;
            }

            return ['message' => 'Failed created virtual account'];
        } catch (\Throwable $th) {
            return ['message' => $th->getMessage()];
        }
    }

    public function inquiry_qris($input = [])
    {
        try {
            $response = Http::withToken(config('app.token'))
                ->get($this->host . 'qris/inquiry/' . $input['purwantara_uuid']);

            $response = $response->json();

            if ($response['success']) {
                $value = $response['data'];
                $return = [
                    'purwantara_uuid' => $value['uuid'],
                    'order_id_merchant' => $value['external_id'],
                    'qris_string' => $value['qr_string'],
                    'qris_url' => $value['qr_url'],
                    'expired' => $value['expired_time'],
                    'payment_status' => $value['status'],
                ];

                return $return;
            }

            return ['message' => 'Failed inquiry qris'];
        } catch (\Throwable $th) {
            return ['message' => $th->getMessage()];
        }
    }

    public function create_payment_link($input = [])
    {
        try {
            $param = [
                'amount' => $input['amount'],
                'title' => $input['title'],
                'description' => $input['description'],
                'expires_at' => Carbon::parse($input['expired_at'])->toIso8601String(),
                'external_id' => $input['external_id'],
                'return_url' => $input['return_url'],
            ];
            $response = Http::withToken(config('purwantara.token'))
                    ->post($this->host . 'payment-link', $param);

            $response = $response->json();

            if ($response['success']) {
                $value = $response['data'];
                $result = [
                    'purwantara_uuid' => $value['uuid'],
                    'order_id_merchant' => $value['external_id'],
                    'amount' => $value['amount'],
                    'payment_status' => $value['status'],
                    'expired' => $value['expires_at'],
                    'return_url' => $value['return_url'],
                    'payment_link_url' => $value['payment_link_url'],
                ];

                return $result;
            }

            return ['message' => 'Failed Create Payment Link'];
        } catch (\Throwable $th) {
            return ['message' => $th->getMessage()];
            //throw $th;
        }
    }
}
