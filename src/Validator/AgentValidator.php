<?php

namespace Lemric\WAF\Validator;

use foroco\BrowserDetection;
use Lemric\WAF\Contracts\ValidateRequest;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;

class AgentValidator implements ValidateRequest
{
    public function __construct(
        #[Autowire('%waf.allowedBrowsers%')] private array $allowedBrowsers = [],
        #[Autowire('%waf.blockedBrowsers%')] private array $blockedBrowsers = [],
        #[Autowire('%waf.allowedPlatforms%')] private array $allowedPlatforms = [],
        #[Autowire('%waf.blockedPlatforms%')] private array $blockedPlatforms = [],
        #[Autowire('%waf.allowedDevices%')] private array $allowedDevices = [],
        #[Autowire('%waf.blockedDevices%')] private array $blockedDevices = []
    ) {
        $this->allowedBrowsers = array_map('strtolower', $this->allowedBrowsers);
        $this->blockedBrowsers = array_map('strtolower', $this->blockedBrowsers);
        $this->allowedPlatforms = array_map('strtolower', $this->allowedPlatforms);
        $this->blockedPlatforms = array_map('strtolower', $this->blockedPlatforms);
        $this->allowedDevices = array_map('strtolower', $this->allowedDevices);
        $this->blockedDevices = array_map('strtolower', $this->blockedDevices);
    }

    public function isValid(Request $request): bool
    {
        $useragent = $request->headers->get('User-Agent');
        $detect = new BrowserDetection();
        $browser = $detect->getBrowser($useragent)['browser_name'] ?? null;
        $platform = $detect->getOS($useragent)['os_family'] ?? null;
        $device = $detect->getDevice($useragent)['device_type'] ?? null;

        if (in_array(strtolower($browser), $this->blockedBrowsers) ||
            in_array(strtolower($platform), $this->blockedPlatforms) ||
            in_array(strtolower($device), $this->blockedDevices)) {
            return false;
        }

        if ((!empty($this->allowedBrowsers) && !in_array(strtolower($browser), $this->allowedBrowsers)) ||
            (!empty($this->allowedPlatforms) && !in_array(strtolower($platform), $this->allowedPlatforms)) ||
            (!empty($this->allowedDevices) && !in_array(strtolower($device), $this->allowedDevices))) {
            return false;
        }

        return true;
    }
}