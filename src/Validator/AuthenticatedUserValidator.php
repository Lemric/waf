<?php

namespace Lemric\WAF\Validator;

use Lemric\WAF\Contracts\ValidateRequest;
use Symfony\Component\HttpFoundation\Request;

readonly class AuthenticatedUserValidator implements ValidateRequest
{
    public function isValid(Request $request): bool
    {
        return $request->getUser() !== null;
    }
}