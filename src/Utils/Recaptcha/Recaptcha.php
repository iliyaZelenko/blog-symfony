<?php

namespace App\Utils\Recaptcha;

use App\Utils\Contracts\Recaptcha\RecaptchaInterface;
use GuzzleHttp\ClientInterface;

class Recaptcha implements RecaptchaInterface
{
    public const CHECK_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var string
     */
    private $secret;

    public function __construct(ClientInterface $client, string $secret)
    {
        $this->client = $client;
        $this->secret = $secret;
    }

    /**
     * @inheritdoc
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function check(string $captchaResponse): bool
    {
        $query = [
            'secret' => $this->secret,
            'response' => $captchaResponse
        ];

        $responseObj = json_decode(
            $this->client->request('POST', static::CHECK_URL, [
                'query' => $query
            ])->getBody()->getContents()
        );

        return (boolean) $responseObj->success;
    }
}
