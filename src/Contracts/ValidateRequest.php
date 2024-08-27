<?php

namespace Lemric\WAF\Contracts;

use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\HttpFoundation\Request;

#[AsTaggedItem('lemric.waf')]
interface ValidateRequest
{
    public function isValid(Request $request): bool;
}