<?php

namespace Validator;

use Lemric\WAF\Validator\SqlInjectionValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class SqlInjectionValidatorTest extends TestCase
{
    public function testRequestWithSqlInjectionIsInvalid()
    {
        $patterns = [
            '#[\d\W](union select|union join|union distinct)[\d\W]#is',
            '#[\d\W](union|union select|insert|from|where|concat|into|cast|truncate|select|delete|having)[\d\W]#is',
        ];

        $validator = new SqlInjectionValidator($patterns);
        $request = new Request(['param' => '1 OR 1=1 UNION SELECT * FROM users']);
        $this->assertFalse($validator->isValid($request));
    }

    public function testRequestWithoutSqlInjectionIsValid()
    {
        $patterns = [
            '#[\d\W](union select|union join|union distinct)[\d\W]#is',
            '#[\d\W](union|union select|insert|from|where|concat|into|cast|truncate|select|delete|having)[\d\W]#is',
        ];

        $validator = new SqlInjectionValidator($patterns);
        $request = new Request(['param' => 'SELECT']);
        $this->assertTrue($validator->isValid($request));
    }
}
