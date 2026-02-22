<?php

if (! function_exists('back_to_url')) {
    function back_to_url(?string $fallback = null): string
    {
        $fallback ??= base_url('/');

        $candidate = service('request')->getGet('backTo')
            ?? session()->get('backTo')
            ?? previous_url();

        if (! is_string($candidate) || $candidate === '') {
            return $fallback;
        }

        if (str_starts_with($candidate, '/')) {
            return site_url(ltrim($candidate, '/'));
        }

        if (! preg_match('#^https?://#i', $candidate)) {
            return $candidate;
        }

        $base = rtrim(base_url('/'), '/');
        if (str_starts_with($candidate, $base)) {
            return $candidate;
        }

        return $fallback;
    }
}
