<?php

declare(strict_types = 1);

namespace Support\Traits;

trait ClearUrl
{
    protected static function clearUrl(?string $string): array|string|null
    {
        if (empty($string)) {
            return null;
        }
        return preg_replace('/([a-zA-Z])\/\//', '$1/', $string);
    }
}
