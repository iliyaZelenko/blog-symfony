<?php

namespace App\Utils\ContentGenerator;

use App\Utils\Contracts\ContentGenerator\ContentGeneratorInterface;

class ContentGenerator implements ContentGeneratorInterface
{
    public const API_URL = 'https://loripsum.net/api/';

    /**
     * @inheritdoc
     */
    public function getRealContent(string $type): ?string
    {
        switch ($type) {
            case 'title':
                return substr(
                    $this->replaceSamePart(
                        strip_tags(
                            // 1 короткий абзац
                            file_get_contents(static::API_URL . '1/short')
                        )
                    )
                    , 0, 80);

            case 'text':
                return $this->replaceSamePart(
                    // контент длинной от 10 до 20 абзацев
                    file_get_contents(static::API_URL . random_int(10, 20))
                );

            case 'textShort':
                return substr(
                    $this->replaceSamePart(
                        // 1 - 2 коротких абзацев
                        file_get_contents(static::API_URL . random_int(1, 2) . '/short')
                    )
                , 0, 255);

            default:
                throw new \Error("Please, use one of 'title', 'text' or 'textShort'.");
        }
    }

    private function replaceSamePart($str)
    {
        return str_replace('Lorem ipsum dolor sit amet, consectetur adipiscing elit. ', '', $str);
    }
}
