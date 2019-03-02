<?php

namespace App\Tests\Unit\Utils\Slugger;

use App\Utils\Contracts\Slugger\SluggerInterface;
use App\Utils\Slugger\Slugger;
use Cocur\Slugify\Slugify;
use PHPUnit\Framework\TestCase;

// Интеграционный тест двух модулей: Slugger и его зависимости $sluggerProcessor
class SluggerTest extends TestCase
{
    private $slugger;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);


        $sluggerProcessor = new Slugify();
        $this->slugger = new Slugger($sluggerProcessor);
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf(SluggerInterface::class, $this->slugger);
    }

    /**
     * @dataProvider slugsDataProvider
     */
    public function testSlugify(string $toSlug, string $expected)
    {
        $this->assertEquals(
            $expected,
            $this->slugger->slugify($toSlug)
        );
    }

    public function slugsDataProvider()
    {
        return [
            ['Тестить довольно интересно, но по началу сложно.', 'testit-dovolno-interesno-no-po-nachalu-slozhno'],
            ['Lorem Ipsum', 'lorem-ipsum'],
            ['  Lorem Ipsum  ', 'lorem-ipsum'],
            [' lOrEm  iPsUm  ', 'lorem-ipsum'],
            ['!Lorem Ipsum!', 'lorem-ipsum'],
            ['lorem-ipsum', 'lorem-ipsum'],
            ['lorem 日本語 ipsum', 'lorem-ipsum'],
            ['lorem русский язык ipsum', 'lorem-russkiy-yazyk-ipsum'],
            ['lorem العَرَبِيَّة‎‎ ipsum', 'lorem-laa-r-b-y-ipsum']
        ];
    }
}
