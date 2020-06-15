<?php


namespace Interfaces;


interface CacheProvider
{
    public static function getInstance();

    public static function usable(): bool;
}
