<?php

if (!function_exists('stripDigits')) {
    function stripDigits(?string $value): ?string
    {
        return null === $value
            ? null
            : preg_replace('/\D/', '', $value);
    }
}
