<?php

namespace Lemric\WAF\Validator;

use Lemric\WAF\Contracts\ValidateRequest;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;

readonly class SqlInjectionValidator implements ValidateRequest
{
    public function __construct(#[Autowire('%waf.sqlPatterns%')] private array $patterns)
    {
    }

    public function isValid(Request $request): bool
    {
        $params = array_merge($request->query->all(), $request->request->all(), $request->headers->all());
        $params = array_map(function($key, $value) {
            return strtolower($key .'='.$value);
        }, array_keys($params), $params);
        $params = implode('&', $params);

        if ($this->containsSqlInjection($params)) {
            return false;
        }

        if ($this->isSuspiciousSize($params)) {
            return false;
        }

        return true;
    }

    private function containsSqlInjection(string $input): bool
    {
        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }

        return false;
    }

    private function isSuspiciousSize(string $input): bool
    {
        return strlen($input) > 1000;
    }
}
