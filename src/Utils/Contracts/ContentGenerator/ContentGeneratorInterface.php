<?php

namespace App\Utils\Contracts\ContentGenerator;

interface ContentGeneratorInterface
{
    /**
     * @param string $type 'title' | 'text' | 'textShort'
     * @return string | null Content
     * @throws \Exception
     */
    public function getRealContent(string $type): ?string;
}
