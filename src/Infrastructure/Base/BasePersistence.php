<?php


namespace App\Infrastructure\Base;


abstract class BasePersistence
{
    public static function createDate(): int
    {
        return date("Ymd");
    }
}