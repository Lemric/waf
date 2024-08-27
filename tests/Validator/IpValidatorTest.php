<?php

namespace Validator;

use Lemric\WAF\Validator\IpValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class IpValidatorTest extends TestCase
{
    public function testBlockedIpIsInvalid()
    {
        $request = new Request([], [], [], [], [], ['REMOTE_ADDR' => '10.0.0.1']);
        $validator = new IpValidator([], ['10.0.0.1']);

        $this->assertFalse($validator->isValid($request));
    }

    public function testAllowedIpIsValid()
    {
        $request = new Request([], [], [], [], [], ['REMOTE_ADDR' => '192.168.0.1']);
        $validator = new IpValidator(['192.168.0.1'], []);

        $this->assertTrue($validator->isValid($request));
    }

    public function testIpNotInAllowedListIsInvalid()
    {
        $request = new Request([], [], [], [], [], ['REMOTE_ADDR' => '192.168.0.2']);
        $validator = new IpValidator(['192.168.0.1'], []);

        $this->assertFalse($validator->isValid($request));
    }

    public function testIpNotInBlockedListIsValid()
    {
        $request = new Request([], [], [], [], [], ['REMOTE_ADDR' => '192.168.0.3']);
        $validator = new IpValidator([], ['10.0.0.1']);

        $this->assertTrue($validator->isValid($request));
    }
}
