<?php

namespace App\Service;

class RecaptchaService
{
    private string $secretKey;
    private string $verifyUrl = "https://www.google.com/recaptcha/api/siteverify";

    public function __construct(string $serverKey)
    {
        $this->secretKey = $serverKey;
    }

    public function verify(string $response): bool
    {
        $res = file_get_contents($this->verifyUrl . '?secret=' . $this->secretKey . '&response=' . $response);
        $responseData = json_decode($res, true);
        return $responseData["score"] > 0.6;
    }
}
