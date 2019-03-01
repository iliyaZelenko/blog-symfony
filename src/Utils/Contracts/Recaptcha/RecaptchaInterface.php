<?php

namespace App\Utils\Contracts\Recaptcha;

interface RecaptchaInterface
{
    /**
     * Validate captcha.
     *
     * @param string $captchaResponse
     * @return bool
     */
    public function check(string $captchaResponse): bool;
}
