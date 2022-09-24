<?php

namespace Ezhasyafaat\PurwantaraPayment;

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

    public function create_virtual_account($input)
    {
        $parameter = [
            'name'              => $input['display_name'],
            'bank'              => $input['channel_name'],
            'external_id'       => $input['order_id_merchant'],
            'merchant_trx_id'   => $input['order_id_merchant'],
            'expected_amount'   => $input['amount'],
            'description'       => isset($input['description']) ? $input['description'] : null,
        ];

        try {
            $response = Http::withToken(config('purwantara.token'))
                ->withBody(json_encode($parameter), 'json')
                ->post($this->host.'virtual-account');

            $data = $response->json();

            if ($data['success'] == true) {
                $value = $data['data'];
                $return = [
                    'purwantara_uuid'   => $value['uuid'],
                    'order_id_merchant' => $value['external_id'],
                    'display_name'      => $value['name'],
                    'channel_name'      => $value['bank'],
                    'amount'            => $value['amount'],
                    'virtual_number'    => $value['va_number'],
                    'description'       => $value['description'],
                    'expired'           => $value['expired_at'],
                    'payment_status'    => $value['status'],
                ];
            } else {
                $return = [
                    'message'    => 'Failed created virtual account',
                ];
            }

            return $return;
        } catch (\Throwable $th) {
            $return['message'] = $th->getMessage();

            return $return;
        }
    }

    public function cancel_virtual_account($input)
    {
        try {
            $response = Http::withToken(config('app.token'))
                ->post($this->host.'virtual-account/cancel/'.$input['purwantara_uuid']);

            $data = $response->json();

            if ($data['success'] == true) {
                $value = $data['data'];
                $return = [
                    'order_id_merchant'     => $value['external_id'],
                    'purwantara_uuid'       => $value['uuid'],
                    'virtual_number'        => $value['va_number'],
                    'message'               => $value['message'],
                ];
            } else {
                $return = [
                    'message'    => 'Failed cancel virtual account',
                ];
            }

            return $return;
        } catch (\Throwable $th) {
            $return['message'] = $th->getMessage();

            return $return;
        }
    }

    public function inquiry_virtual_account($input)
    {
        try {
            $response = Http::withToken(config('app.token'))
                ->get($this->host.'virtual-account/inquiry/'.$input['purwantara_uuid']);

            $data = $response->json();

            if ($data['success'] == true) {
                $value = $data['data'];
                $return = [
                    'purwantara_uuid'   => $value['uuid'],
                    'order_id_merchant' => $value['external_id'],
                    'display_name'      => $value['name'],
                    'channel_name'      => $value['bank'],
                    'virtual_number'    => $value['va_number'],
                    'amount'            => $value['amount'],
                    'description'       => $value['description'],
                    'expired'           => $value['expired_at'],
                    'payment_status'    => $value['status'],
                ];
            } else {
                $return = [
                    'message'    => 'Failed inquiry virtual account',
                ];
            }

            return $return;
        } catch (\Throwable $th) {
            $return['message'] = $th->getMessage();

            return $return;
        }
    }

    public function create_qris($input)
    {
        $parameter = [
            'amount'                    => $input['amount'],
            'customer_email'            => $input['customer_email'],
            'customer_first_name'       => $input['customer_first_name'],
            'customer_last_name'        => $input['customer_last_name'],
            'customer_phon'             => $input['customer_phone'],
            'transaction_description'   => isset($input['description']) ? $input['description'] : null,
            'payment_channel'           => $input['channel_name'],
            'order_id'                  => $input['order_id_merchant'],
            'merchant_trx_id'           => $input['order_id_merchant'],
            'payment_method'            => 'wallet',
        ];

        try {
            $response = Http::withToken(config('purwantara.token'))
                ->withBody(json_encode($parameter), 'json')
                ->post($this->host.'qris');

            $data = $response->json();

            if ($data['success'] == true) {
                $value = $data['data'];
                $return = [
                    'purwantara_uuid'   => $value['uuid'],
                    'order_id_merchant' => $value['external_id'],
                    'qris_string'       => $value['qr_string'],
                    'qris_url'          => $value['qr_url'],
                    'expired'           => $value['expired_time'],
                    'payment_status'    => $value['status'],
                ];
            } else {
                $return = [
                    'message'    => 'Failed created virtual account',
                ];
            }

            return $return;
        } catch (\Throwable $th) {
            $return['message'] = $th->getMessage();

            return $return;
        }
    }

    public function inquiry_qris($input)
    {
        try {
            $response = Http::withToken(config('app.token'))
                ->get($this->host.'qris/inquiry/'.$input['purwantara_uuid']);

            $data = $response->json();

            if ($data['success'] == true) {
                $value = $data['data'];
                $return = [
                    'purwantara_uuid'   => $value['uuid'],
                    'order_id_merchant' => $value['external_id'],
                    'qris_string'       => $value['qr_string'],
                    'qris_url'          => $value['qr_url'],
                    'expired'           => $value['expired_time'],
                    'payment_status'    => $value['status'],
                ];
            } else {
                $return = [
                    'message'    => 'Failed inquiry qris',
                ];
            }

            return $return;
        } catch (\Throwable $th) {
            $return['message'] = $th->getMessage();

            return $return;
        }
    }
}
