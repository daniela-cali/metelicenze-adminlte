<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RememberBackToFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! $request instanceof IncomingRequest) {
            return;
        }

        if (strtoupper($request->getMethod()) !== 'GET') {
            return;
        }

        if ($request->isAJAX()) {
            return;
        }

        $backToFromGet = $request->getGet('backTo');
        if (is_string($backToFromGet) && $backToFromGet !== '') {
            session()->set('backTo', $backToFromGet);
            return;
        }

        $referer = $request->getHeaderLine('Referer');
        if ($referer !== '' && $referer !== current_url()) {
            session()->set('backTo', $referer);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
