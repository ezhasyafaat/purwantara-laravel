<?php

namespace Ezhasyafaat\PurwantaraPayment;

use Illuminate\Support\Facades\Http;

class Purwantara {
    
    public const BASE_URL      = "https://api.purwantara.id/v1/";

    public function create_virtual_account($input)
    {   
        $parameter  = [
            'name'              => $input['display_name'],
            'bank'              => $input['channel_name'],
            'external_id'       => $input['order_id_merchant'],
            'merchant_trx_id'   => $input['order_id_merchant'],
            'expected_amount'   => $input['amount'],
            'description'       => isset($input['description']) ? $input['description'] : null,
        ];

        try {
            $response   = Http::withToken(config('purwantara.token'))
                ->withBody(json_encode($parameter), 'json')
                ->post(self::BASE_URL . 'virtual-account');
        
            $data   = $response->json();

            if ($data['success'] == true) {
                $value      = $data['data'];
                $return     = [
                    'purwantara_uuid'   => $value['uuid'],
                    'order_id_merchant' => $value['external_id'],
                    'display_name'      => $value['name'],
                    'channel_name'      => $value['bank'],
                    'amount'            => $value['amount'],
                    'virtual_number'    => $value['va_number'],
                    'description'       => $value['description'],
                    'expired'           => $value['expired_at'],
                    'payment_status'    => $value['status']            
                ];
            } else {
                $return     = [
                    'message'    => 'Failed created virtual account',
                ];
            }

            return $return;
        } catch (\Throwable $th) {
            $return['message']  = $th->getMessage();

            return $return;
        }
    }

    public function cancel_virtual_account($input)
    {
        try {
            $response   = Http::withToken(config('app.token'))
                ->post(self::BASE_URL . 'virtual-account/cancel/' . $input['purwantara_uuid']);
            
            $data   = $response->json();

            if ($data['success'] == true) {
                $value      = $data['data'];
                $return     = [
                    'order_id_merchant'     => $value['external_id'],
                    'purwantara_uuid'       => $value['uuid'],
                    'virtual_number'        => $value['va_number'],
                    'message'               => $value['message']
                ];
            } else {
                $return     = [
                    'message'    => 'Failed cancel virtual account',
                ];
            }

            return $return;
        } catch (\Throwable $th) {
            $return['message']  = $th->getMessage();

            return $return;
        }
    }

    public function inquiry_virtual_account($input)
    {
        try {
            $response   = Http::withToken(config('app.token'))
                ->get(self::BASE_URL . 'virtual-account/inquiry/' . $input['purwantara_uuid']);
            
            $data   = $response->json();

            if ($data['success'] == true) {
                $value      = $data['data'];
                $return     = [
                    'purwantara_uuid'   => $value['uuid'],
                    'order_id_merchant' => $value['external_id'],
                    'display_name'      => $value['name'],
                    'channel_name'      => $value['bank'],
                    'virtual_number'    => $value['va_number'],
                    'amount'            => $value['amount'],
                    'description'       => $value['description'],
                    'expired'           => $value['expired_at'],
                    'payment_status'    => $value['status']            
                ];
            } else {
                $return     = [
                    'message'    => 'Failed inquiry virtual account',
                ];
            }

            return $return;
        } catch (\Throwable $th) {
            $return['message']  = $th->getMessage();

            return $return;
        }
    }
}