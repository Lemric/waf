<?php

namespace Validator;

use Lemric\WAF\Validator\XssValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class XssValidatorTest extends TestCase
{
    public function testRequestWithXssIsInvalid()
    {
        $patterns = [
            '#<script\b[^>]*>(.*?)<\/script>#is',
        ];

        $validator = new XssValidator($patterns);

        $request = new Request(['param' => '<script>alert("XSS")</script>']);

        $this->assertFalse($validator->isValid($request));
    }

    public function testRequestWithoutXssIsValid()
    {
        $patterns = [
            '#<script\b[^>]*>(.*?)<\/script>#is',
        ];

        $validator = new XssValidator($patterns);

        $request = new Request(['param' => 'Hello, World!']);

        $this->assertTrue($validator->isValid($request));
    }
}