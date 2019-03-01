<?php

namespace App\Utils\Mail;

use App\Utils\Contracts\Mail\MailInterface;

class Mail implements MailInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    private $siteEmail;

    public function __construct(\Swift_Mailer $mailer, $siteEmail)
    {
        $this->mailer = $mailer;
        $this->siteEmail = $siteEmail;
    }

    public function send(string $to, string $subject, string $body): void
    {
        $message = (new \Swift_Message($subject))
            ->setFrom($this->siteEmail)
            ->setTo($to)
            ->setBody($body, 'text/html')
        ;

        $this->mailer->send($message);
    }
}
