<?php

namespace App\Utils\Slugger;

use App\Utils\Contracts\Slugger\SluggerInterface;

// Это приложение использует Slugger по интерфейсу SluggerInterface, понимаю что можно было сразу использовать
// \Cocur\Slugify\SlugifyInterface, но если я захочу поменять реализацию Slugger, то смогу просто поменять этот класс
// Может для простого слугера это лишнее, но я хотел протестировать такой класс.
class Slugger implements SluggerInterface
{
    /**
     * Есть поддержка транслитерации
     *
     * @var \Cocur\Slugify\SlugifyInterface
     */
    private $sluggerProcessor;

    public function __construct(\Cocur\Slugify\SlugifyInterface $sluggerProcessor)
    {
        $this->sluggerProcessor = $sluggerProcessor;
    }

    public function slugify($str): string
    {
        return $this->sluggerProcessor->slugify($str);
    }
}
