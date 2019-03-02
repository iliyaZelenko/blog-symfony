<?php

namespace App\Utils\Slugger;

use App\Utils\Contracts\Slugger\SluggerInterface;

class Slugger implements SluggerInterface
{
    /**
     * Есть поддержка транслитерации
     *
     * @var \Cocur\Slugify\SlugifyInterface
     */
    private $slugify;

    public function __construct(\Cocur\Slugify\SlugifyInterface $slugify)
    {
        $this->slugify = $slugify;
    }

    public function slugify($str): string
    {
        return $this->slugify->slugify($str);
    }
}
