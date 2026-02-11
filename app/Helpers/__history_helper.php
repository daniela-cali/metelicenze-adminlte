<?php
if (! function_exists('setHistory')) {
    function setHistory($fromUrl)
    {
        $history = session()->get('history', []);
        if (!is_array($history)) {
            $history = [];
        } else {
            //$history = array_filter($history, fn($url) => !is_null($url) && $url !== '');
            log_message('info', 'history_helper::setHistory - History: ' . print_r($history, true));

            if (in_array($fromUrl, $history)) {
                // Rimuovi le occorrenze precedenti per evitare duplicati
                $history = array_filter($history, fn($url) => $url !== $fromUrl);
                // Aggiungi l'URL corrente alla fine
                $history[] = $fromUrl;
            } else {
                // Aggiungi l'URL corrente alla fine
                $history[] = $fromUrl;
            }
            session()->set('history', $history);
            log_message('info', 'history_helper::setHistory - Updated History: ' . print_r($history, true));
        }
    }
}

if (! function_exists('getBackTo')) {
    function getBackTo()
    {
        $history = session()->get('history', []);
        if (!is_array($history)) {
            $history = [];
            log_message('info', 'history_helper::getBackTo - History is not an array, returning null.');
        } else {
            //$history = array_filter($history, fn($url) => !is_null($url) && $url !== '');
            log_message('info', 'history_helper::getBackTo - History: ' . print_r($history, true));
            $history = array_pop($history); // Rimuovi l'URL corrente
            log_message('info', 'history_helper::getBackTo - Popped History: ' . print_r($history, true));
            $fromUrl = end($history); // Prendi l'URL precedente
            session()->set('history', $history);
            log_message('info', 'history_helper::getBackTo - Updated History: ' . print_r($history, true));
            return $fromUrl;
        }
    }
}
