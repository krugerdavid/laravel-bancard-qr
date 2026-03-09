<?php

namespace KrugerDavid\LaravelBancardQR;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Utils;
use Illuminate\Support\Facades\Log;

class BancardQRManager
{
    protected Client $client;
    protected string $baseUrl;
    protected string $privateKey;
    protected string $publicKey;
    protected string $commerceCode;
    protected string $commerceBranch;

    public const PRODUCTION_URL = 'https://comercios.bancard.com.py/external-commerce/api/0.1/';
    public const STAGING_URL = 'https://vpos.infonet.com.py:8888/';

    public function __construct(array $config)
    {
        $this->baseUrl = $config['staging'] ?? true
            ? rtrim(self::STAGING_URL, '/') . '/'
            : rtrim(self::PRODUCTION_URL, '/') . '/';

        $this->privateKey = $config['private_key'];
        $this->publicKey = $config['public_key'];
        $this->commerceCode = $config['commerce_code'];
        $this->commerceBranch = $config['commerce_branch'];

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => $config['timeout'] ?? 30,
        ]);
    }

    /**
     * Generate a QR code for a payment.
     *
     * @param  int  $amount  Amount in guaraníes
     * @param  string  $description  Description of the sale
     * @param  array|null  $promotions  Optional promotions array
     * @return object Response with status, qr_express, supported_clients
     *
     * @throws \Exception
     */
    public function generateQr(int $amount, string $description, ?array $promotions = null): object
    {
        $url = "commerces/{$this->commerceCode}/branches/{$this->commerceBranch}/selling/generate-qr-express";

        $json = [
            'amount' => $amount,
            'description' => $description,
        ];

        if ($promotions && is_array($promotions)) {
            $json['promotions'] = $promotions;
        }

        try {
            $response = $this->client->post($url, [
                'headers' => $this->getHeaders(),
                'json' => $json,
            ]);

            return Utils::jsonDecode($response->getBody()->getContents());
        } catch (GuzzleException $e) {
            Log::error('BancardQR generateQr error', ['error' => $e->getMessage()]);
            throw $this->formatException($e);
        }
    }

    /**
     * Revert a QR payment by hook alias.
     *
     * @param  string  $hookAlias  The QR alias from the payment
     * @return object Response with status, reverse, messages
     *
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function revert(string $hookAlias): object
    {
        if (empty(trim($hookAlias))) {
            throw new \InvalidArgumentException('Hook alias cannot be empty');
        }

        $url = "commerces/{$this->commerceCode}/branches/{$this->commerceBranch}/selling/payments/revert/{$hookAlias}";

        try {
            $response = $this->client->put($url, [
                'headers' => $this->getHeadersForRevert(),
            ]);

            return Utils::jsonDecode($response->getBody()->getContents());
        } catch (GuzzleException $e) {
            Log::error('BancardQR revert error', ['error' => $e->getMessage(), 'hook_alias' => $hookAlias]);
            throw $this->formatException($e);
        }
    }

    protected function getHeaders(): array
    {
        return [
            'Authorization' => $this->generateToken(),
            'Content-Type' => 'application/json',
        ];
    }

    protected function getHeadersForRevert(): array
    {
        $headers = $this->getHeaders();
        $headers['referer'] = true;
        $headers['User-Agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'Laravel-BancardQR/2.0';
        $headers['accept'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
        $headers['Accept-Encoding'] = 'gzip, deflate, br';

        return $headers;
    }

    protected function generateToken(): string
    {
        $token = base64_encode(sprintf('apps/%s:%s', $this->publicKey, $this->privateKey));

        return "Basic {$token}";
    }

    protected function formatException(GuzzleException $e): \Exception
    {
        $message = $e->getMessage();
        $response = $e->getResponse();

        if ($response !== null) {
            try {
                $body = Utils::jsonDecode($response->getBody()->getContents());
                if (isset($body->messages) && is_array($body->messages)) {
                    $message = implode('; ', array_column($body->messages, 'key'));
                } elseif (isset($body->messages)) {
                    $message = (string) $body->messages;
                }
            } catch (\Throwable) {
                // Fallback to original message
            }
        }

        return new \Exception("Bancard QR API error: {$message}", $e->getCode(), $e);
    }
}
