<?php

namespace App\Utils\Contracts\Mail;

interface MailInterface
{
    public function send(string $to, string $subject, string $body): void;
}
