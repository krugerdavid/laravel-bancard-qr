<?php

namespace KrugerDavid\LaravelBancardQR;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Utils;

class BancardQR
{
    /**
     * 
     */
    public static function generate_qr(int $amount, string $description, ?array $promotions = null)
    {
        if (is_null($amount) || empty($amount))
            throw new Exception("amount value is empty");

        if (is_null($description) || empty($description) || !isset($description))
            throw new Exception("desription is empty");

        try 
        {
            $url = 'commerces/%s/branches/%s/selling/generate-qr-express';
            $url = sprintf($url, config("bancard-qr.commerce.code"), config("bancard-qr.commerce.branch"));

            $headers = [
                'Authorization' => self::generateToken(),
                'Content-Type' => 'application/json'
            ];

            $client = new Client([
                'base_uri' => config('bancard-qr.service_url')
            ]);

            $json = [
                'amount' => $amount,
                'description' => $description
            ];

            if($promotions && is_array($promotions))
                $json['promotions'] = $promotions;            

            $response = $client->request('POST',$url, [
                'headers' => $headers,
                'body' => json_encode($json)
            ]);

            return self::responseJson($response);

        } catch (RequestException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function revert(string $hook_alias)
    {
        if(is_null($hook_alias) || empty($hook_alias))
            throw new Exception("Hook alias is empty");
            
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

            return self::responseJson($res);

        } catch (RequestException $e) {
            throw new Exception($e->getMessage());
        }
    }

    private static function generateToken()
    {
        $token = base64_encode(sprintf('apps/%s:%s', config('bancard-qr.public_key'), config('bancard-qr.private_key')));
        return "Basic {$token}";
    }

    private static function responseJson($response)
    {
        return Utils::jsonDecode(
            $response->getBody()->getContents()
        );
    }
}