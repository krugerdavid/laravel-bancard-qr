<?php

namespace KrugerDavid\LaravelBancardQR;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Utils;

class BancardQR
{
    public static string $production = 'https://comercios.bancard.com.py/external-commerce/api/0.1/';
    public static string $staging = 'https://vpos.infonet.com.py:8888';

    public static function isStaging() : bool
    {
        return config('bancardqr.staging');
    }

    public static function baseUrl() : string 
    {
        return self::isStaging() ? self::$staging : self::$production;
    }

    /**
     * 
     */
    public static function generate_qr(int $amount, string $description, ?array $promotions = null)
    {
        try 
        {
            $url = 'commerces/%s/branches/%s/selling/generate-qr-express';
            $url = sprintf($url, config('bancardqr.commerce_code'), config('bancardqr.commerce_branch'));

            $headers = [
                'Authorization' => self::generateToken(),
                'Content-Type' => 'application/json'
            ];

            $client = new Client([
                'base_uri' => self::baseUrl()
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
            return self::formatException($e);
        }
    }

    public static function revert(string $hook_alias)
    {
        if(is_null($hook_alias) || empty($hook_alias))
            throw new Exception("Hook alias is empty");
            
        try 
        {
            $url = 'commerces/%s/branches/%s/selling/payments/revert/%s';
            $url = sprintf($url, config('bancardqr.commerce.code'), config('bancardqr.commerce.branch'), $hook_alias);

            $headers = [
                'Authorization' => self::generateToken(),
                'Content-Type' => 'application/json',
                'referer' => true,
                'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
                'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'Accept-Encoding' => 'gzip, deflate, br',
            ];

            $client = new Client([
                'base_uri' => self::baseUrl()
            ]);

            $res = $client->request('PUT', $url, [
                'headers' => $headers
            ]);

            return self::responseJson($res);

        } catch (RequestException $e) {
            return self::formatException($e);
        }
    }

    private static function generateToken()
    {
        $token = base64_encode(sprintf('apps/%s:%s', config('bancardqr.public_key'), config('bancardqr.private_key')));
        return "Basic {$token}";
    }

    private static function responseJson($response)
    {
        return Utils::jsonDecode(
            $response->getBody()->getContents()
        );
    }

    private static function formatException($e)
    {
        return Utils::jsonDecode(
            $e->getResponse()->getBody()->getContents()
        );
    }
}