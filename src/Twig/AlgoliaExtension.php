<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AlgoliaExtension extends AbstractExtension
{
    /**
     * @var array
     */
    private $indexes;

    /**
     * @var string
     */
    private $indexPrefix;

    public function __construct(array $indexes, string $indexPrefix)
    {
        $this->indexes = $indexes;
        $this->indexPrefix = $indexPrefix;
    }

    public function getFunctions(): array
    {
        return [
            // возвращает полное имя индекса Algolia (используется префикс)
            new TwigFunction('appAlgoliaGetIndexName', function ($indexKey) {
                $indexName = $this->indexes[$indexKey];

                return $this->indexPrefix . '_' . $indexName;
            })
        ];
    }
}
