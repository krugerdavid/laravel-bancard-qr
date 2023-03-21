<?php

namespace KrugerDavid\LaravelBancardQR;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;

class BancardQR
{
    /**
     * 
     */
    public static function generate_qr(int $amount, ?string $description = null, ?array $promotions = null)
    {
        $headers = [
            'Authorization' => self::generateToken(),
            'Content-Type' => 'application/json'
        ];

        try 
        {
            $url = 'commerces/%s/branches/%s/selling/generate-qr-express';
            $url = sprintf($url, config("bancard-qr.commerce.code"), config("bancard-qr.commerce.branch"));

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
                'headers' => $headers,
                'body' => json_encode($json)
            ]);

            return response()->json(self::responseFromBancard($res));

        } catch (GuzzleException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public static function revert(string $hook_alias)
    {
        try 
        {
            $url = 'commerces/%s/branches/%s/selling/payments/revert/%s';
            $url = sprintf($url, config("bancard-qr.commerce.code"), config("bancard-qr.commerce.branch"), $hook_alias);

            $headers = [
                'Authorization' => self::generateToken(),
                'Content-Type' => 'application/json'
            ];

            $client = new Client([
                'base_uri' => config('bancard-qr.service_url')
            ]);

            $res = $client->request('PUT', $url, [
                'headers' => $headers
            ]);

            return response()->json(self::responseFromBancard($res));

        } catch (GuzzleException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private static function generateToken()
    {
        $token = base64_encode(sprintf('apps/%s:%s', config('bancard-qr.public_key'), config('bancard-qr.private_key')));
        return "Basic {$token}";
    }

    private static function responseFromBancard(Response $response)
    {
        $res = @json_decode((string)$response->getBody()->getContents());
        return $res;
    }
}