<?php

namespace Lemric\WAF\Validator;

use Lemric\WAF\Contracts\ValidateRequest;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class FileUploadValidator implements ValidateRequest
{
    public function __construct(#[Autowire('%waf.allowedMimeTypes%')] private array  $allowedMimeTypes = [],
                                #[Autowire('%waf.blockedMimeTypes%')] private array $blockedMimeTypes = [],
                                #[Autowire('%waf.blockedExtensions%')] private array $blockedExtensions = []
    )
    {
    }

    public function isValid(Request $request): bool
    {
        $files = $request->files->all();

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                if (!$this->validateFile($file)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function validateFile(UploadedFile $file): bool
    {
        if (!empty($this->allowedMimeTypes) && !in_array($file->getMimeType(), $this->allowedMimeTypes)) {
            return false;
        }

        if (!empty($this->blockedMimeTypes) && in_array($file->getMimeType(), $this->blockedMimeTypes)) {
            return false;
        }

        if (!empty($this->blockedExtensions) && in_array($file->getClientOriginalExtension(), $this->blockedExtensions)) {
            return false;
        }

        return true;
    }
}
