<?php

namespace KrugerDavid\LaravelBancardQR;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class BancardQR
{
    private static function generateToken()
    {
        $token = base64_encode("apps/" . config('bancard-qr.public_key') . ":" . config('bancard-qr.private_key'));
        return "Basic {$token}";
    }

    public static function generate_qr(int $amount, ?string $description = null, ?array $promotions = null)
    {
        $headers = [
            'Authorization: ' . self::generateToken(),
            'Content-Type: application/json'
        ];
        Log::debug('Response', ['headers' => $headers]);

        try {
            $url = "commerces/". config("bancard-qr.commerce.code") . "/branches/" . config("bancard-qr.commerce.branch") . "/selling/generate-qr-express";

            $client = new Client([
                'base_uri' => config('bancard-qr.service_url')
            ]);

            $json = [
                'amount' => $amount
            ];

            if($description)
                $json['description'] = $description;

            if($promotions)
                $json['promotions'] = $promotions;

            

            $res = $client->request('POST',$url, [
                'json' => $json,
                'headers' => $headers
            ]);


            return response()->json([
                'code' => $res->getStatusCode()
            ]);

        } catch (GuzzleException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}