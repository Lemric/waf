<?php

namespace Lemric\WAF\Tests\Validator;

use Lemric\WAF\Validator\FileUploadValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class FileUploadValidatorTest extends TestCase
{
    public function testValidFileUpload()
    {
        $validator = new FileUploadValidator(['image/jpeg'], [], ['php']);

        $file = $this->createMock(UploadedFile::class);
        $file->method('getMimeType')->willReturn('image/jpeg');
        $file->method('getClientOriginalExtension')->willReturn('jpg');

        $request = new Request([], [], [], [], ['file' => $file]);

        $this->assertTrue($validator->isValid($request));
    }

    public function testInvalidMimeType()
    {
        $validator = new FileUploadValidator(['image/jpeg'], [], ['php']);

        $file = $this->createMock(UploadedFile::class);
        $file->method('getMimeType')->willReturn('application/pdf');
        $file->method('getClientOriginalExtension')->willReturn('pdf');

        $request = new Request([], [], [], [], ['file' => $file]);

        $this->assertFalse($validator->isValid($request));
    }
    public function testBlockedMimeType()
    {
        $validator = new FileUploadValidator([], ['image/jpeg'], ['php']);

        $file = $this->createMock(UploadedFile::class);
        $file->method('getMimeType')->willReturn('image/jpeg');
        $file->method('getClientOriginalExtension')->willReturn('jpg');

        $request = new Request([], [], [], [], ['file' => $file]);

        $this->assertFalse($validator->isValid($request));
    }

    public function testBlockedExtension()
    {
        $validator = new FileUploadValidator(['image/jpeg'], [], ['php']);

        $file = $this->createMock(UploadedFile::class);
        $file->method('getMimeType')->willReturn('image/jpeg');
        $file->method('getClientOriginalExtension')->willReturn('php');

        $request = new Request([], [], [], [], ['file' => $file]);

        $this->assertFalse($validator->isValid($request));
    }
}
