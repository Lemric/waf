<?php

namespace Lemric\WAF\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

readonly class RequestListener
{
    public function __construct(private iterable $validators)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        foreach ($this->validators as $validator) {
            if (!$validator->isValid($request)) {
                throw new AccessDeniedHttpException('Access denied by WAF');
            }
        }
    }
}