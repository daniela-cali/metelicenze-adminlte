<?php

if (! function_exists('back_to_url')) {
    /**
     * Restituisce l'URL precedente oppure il $fallback indicato.
     * Usato come ultima rete di sicurezza nelle view quando il controller
     * non ha passato un $backTo esplicito.
     */
    function back_to_url(?string $fallback = null): string
    {
        return previous_url() ?: ($fallback ?? base_url('/'));
    }
}
