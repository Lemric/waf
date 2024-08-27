<?php

namespace EventListener;

use Lemric\WAF\Contracts\ValidateRequest;
use Lemric\WAF\EventListener\RequestListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class RequestListenerTest extends TestCase
{
    public function testValidRequestPasses()
    {
        $validator = $this->createMock(ValidateRequest::class);
        $validator->method('isValid')->willReturn(true);

        $listener = new RequestListener([$validator]);

        $kernel = $this->createMock(HttpKernelInterface::class);
        $request = new Request();
        $event = new RequestEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST);

        // Expect no exception to be thrown
        $listener->onKernelRequest($event);

        $this->assertTrue(true); // Dummy assertion to denote the test passed
    }

    public function testInvalidRequestThrowsException()
    {
        $validator = $this->createMock(ValidateRequest::class);
        $validator->method('isValid')->willReturn(false);

        $listener = new RequestListener([$validator]);

        $kernel = $this->createMock(HttpKernelInterface::class);
        $request = new Request();
        $event = new RequestEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST);

        $this->expectException(AccessDeniedHttpException::class);

        $listener->onKernelRequest($event);
    }

    public function testMultipleValidatorsAllValid()
    {
        $validator1 = $this->createMock(ValidateRequest::class);
        $validator1->method('isValid')->willReturn(true);

        $validator2 = $this->createMock(ValidateRequest::class);
        $validator2->method('isValid')->willReturn(true);

        $listener = new RequestListener([$validator1, $validator2]);

        $kernel = $this->createMock(HttpKernelInterface::class);
        $request = new Request();
        $event = new RequestEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST);

        // Expect no exception to be thrown
        $listener->onKernelRequest($event);

        $this->assertTrue(true); // Dummy assertion to denote the test passed
    }

    public function testMultipleValidatorsOneInvalid()
    {
        $validator1 = $this->createMock(ValidateRequest::class);
        $validator1->method('isValid')->willReturn(true);

        $validator2 = $this->createMock(ValidateRequest::class);
        $validator2->method('isValid')->willReturn(false);

        $listener = new RequestListener([$validator1, $validator2]);

        $kernel = $this->createMock(HttpKernelInterface::class);
        $request = new Request();
        $event = new RequestEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST);

        $this->expectException(AccessDeniedHttpException::class);

        $listener->onKernelRequest($event);
    }
}
