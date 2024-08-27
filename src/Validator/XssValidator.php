<?php

namespace Lemric\WAF\Validator;

use Lemric\WAF\Contracts\ValidateRequest;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;

readonly class XssValidator implements ValidateRequest
{
    public function __construct(#[Autowire('%waf.xssPatterns%')] private array $patterns)
    {
    }

    public function isValid(Request $request): bool
    {
        $params = array_merge($request->query->all(), $request->request->all());

        foreach ($params as $param) {
            if (is_array($param)) {
                $param = implode(' ', $param);
            }

            foreach ($this->patterns as $pattern) {
                if (preg_match($pattern, $param)) {
                    return false;
                }
            }
        }

        return true;
    }
}