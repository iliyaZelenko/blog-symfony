<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DateExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('blogDate', function (\DateTimeInterface $date) {
                $html = '<blog-date date="' . $date->format('U') . '"></blog-date>';

                return new \Twig\Markup($html, 'UTF-8');
            }),
        ];
    }
}
