<?php

namespace App\DataFixtures\Traits;

trait RandomReference
{
//    Было бы классно как в TypeScript объявлять абстрактные свойства.
//    public abstract const REFERENCE_PREFIX;
//    public abstract const COUNT;

    public static function getCount()
    {
        return static::COUNT;
    }

    public static function getRandomReference()
    {
        return static::REFERENCE_PREFIX . random_int(1, static::getCount());
    }
}
