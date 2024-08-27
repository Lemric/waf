<?php

namespace Lemric\WAF\Validator;

use Lemric\WAF\Contracts\ValidateRequest;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;

readonly class CsrfValidator implements ValidateRequest
{

    public function __construct(#[Autowire('%waf.allowedOrigins%')] private array $allowedOrigins)
    {
    }

    public function isValid(Request $request): bool
    {
        $origin = $request->headers->get('Origin');
        $referer = $request->headers->get('Referer');

        if ($origin && !in_array($origin, $this->allowedOrigins)) {
            return false;
        }

        if ($referer && !$this->isValidReferer($referer)) {
            return false;
        }

        return true;
    }

    private function isValidReferer(string $referer): bool
    {
        foreach ($this->allowedOrigins as $allowedOrigin) {
            if (str_starts_with($referer, $allowedOrigin)) {
                return true;
            }
        }

        return false;
    }
}