<?php

namespace Lemric\WAF\Validator;

use Lemric\WAF\Contracts\ValidateRequest;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\HttpFoundation\Request;

readonly class PathValidator implements ValidateRequest
{
    public function __construct(#[Autowire('%waf.blockedPaths%')] private array $blockedPaths)
    {
    }

    public function isValid(Request $request): bool
    {
        return !in_array($request->getPathInfo(), $this->blockedPaths, true);
    }
}