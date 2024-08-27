<?php

namespace Validator;

use foroco\BrowserDetection;
use Lemric\WAF\Validator\AgentValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AgentValidatorTest extends TestCase
{
    public function testBlockedBrowserIsInvalid()
    {
        $validator = new AgentValidator([], ['Firefox'], [], [], [], []);
        $request = new Request();
        $request->headers->set('User-Agent', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0');

        $this->assertFalse($validator->isValid($request));
    }

    public function testAllowedBrowserIsValid()
    {
        $validator = new AgentValidator(['Firefox'], [], [], [], [], []);

        $request = new Request();
        $request->headers->set('User-Agent', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0');

        $this->assertTrue($validator->isValid($request));
    }

    public function testBlockedPlatformIsInvalid()
    {
        $validator = new AgentValidator([], [], [], ['Windows'], [], []);

        $request = new Request();
        $request->headers->set('User-Agent', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0');

        $this->assertFalse($validator->isValid($request));
    }

    public function testAllowedDeviceIsValid()
    {
        $validator = new AgentValidator([], [], [], [], ['Desktop'], []);

        $request = new Request();
        $request->headers->set('User-Agent', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0');

        $this->assertTrue($validator->isValid($request));
    }
}