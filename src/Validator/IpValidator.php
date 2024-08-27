<?php

namespace Lemric\WAF\Validator;

use Lemric\WAF\Contracts\ValidateRequest;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;

readonly class IpValidator implements ValidateRequest
{
    public function __construct(#[Autowire('%waf.allowedIps%')] private array $allowedIps = [],
                                #[Autowire('%waf.blockedIps%')] private array $blockedIps = [])
    {
    }

    public function isValid(Request $request): bool
    {
        $clientIp = $request->getClientIp();

        if (in_array($clientIp, $this->blockedIps)) {
            return false;
        }

        if (!empty($this->allowedIps) && !in_array($clientIp, $this->allowedIps)) {
            return false;
        }

        return true;
    }
}