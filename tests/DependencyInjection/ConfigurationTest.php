<?php

namespace Lemric\WAF\Tests\DependencyInjection;

use Lemric\WAF\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ConfigurationTest extends TestCase
{
    private ConfigurationInterface $configuration;

    protected function setUp(): void
    {
        $this->configuration = new Configuration();
    }

    public function testDefaultConfiguration()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration($this->configuration, []);

        $expectedConfig = [
            'allowedOrigins' => [],
            'blockedIps' => [],
            'allowedIps' => [],
            'blockedPaths' => [],
            'allowedBrowsers' => [],
            'blockedBrowsers' => [],
            'allowedPlatforms' => [],
            'blockedPlatforms' => [],
            'allowedDevices' => [],
            'blockedDevices' => [],
            'allowedMimeTypes' => [],
            'blockedMimeTypes' => [],
            'blockedExtensions' => [],
            'xssPatterns' => [
                '#(<[^>]+[\x00-\x20\"\'\/])(form|formaction|on\w*|style|xmlns|xlink:href)[^>]*>?#iUu',
                '!((java|live|vb)script|mocha|feed|data):(\w)*!iUu',
                '#-moz-binding[\x00-\x20]*:#u',
                '#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base|img)[^>]*>?#i'
            ],
            'sqlPatterns' => [
                '#[\d\W](union select|union join|union distinct)[\d\W]#is',
                '#[\d\W](union|union select|insert|from|where|concat|into|cast|truncate|select|delete|having)[\d\W]#is'
            ],
        ];

        $this->assertSame($expectedConfig, $config);
    }

    public function testCustomConfiguration()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration($this->configuration, [
            'waf' => [
                'allowedOrigins' => ['https://example.com'],
                'blockedIps' => ['192.168.1.1'],
                'allowedIps' => ['192.168.1.2'],
                'blockedPaths' => ['/admin'],
                'allowedBrowsers' => ['Firefox'],
                'blockedBrowsers' => ['IE'],
                'allowedPlatforms' => ['Linux'],
                'blockedPlatforms' => ['Windows'],
                'allowedDevices' => ['Desktop'],
                'blockedDevices' => ['Tablet'],
                'xssPatterns' => ['#<script>#'],
                'sqlPatterns' => ['#SELECT \* FROM users#'],
            ],
        ]);

        $expectedConfig = [
            'allowedOrigins' => ['https://example.com'],
            'blockedIps' => ['192.168.1.1'],
            'allowedIps' => ['192.168.1.2'],
            'blockedPaths' => ['/admin'],
            'allowedBrowsers' => ['Firefox'],
            'blockedBrowsers' => ['IE'],
            'allowedPlatforms' => ['Linux'],
            'blockedPlatforms' => ['Windows'],
            'allowedDevices' => ['Desktop'],
            'blockedDevices' => ['Tablet'],
            'xssPatterns' => ['#<script>#'],
            'sqlPatterns' => ['#SELECT \* FROM users#'],
            'allowedMimeTypes' => [],
            'blockedMimeTypes' => [],
            'blockedExtensions' => [],
        ];

        $this->assertSame($expectedConfig, $config);
    }

    public function testEmptyConfiguration()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration($this->configuration, [
            'waf' => [],
        ]);

        $expectedConfig = [
            'allowedOrigins' => [],
            'blockedIps' => [],
            'allowedIps' => [],
            'blockedPaths' => [],
            'allowedBrowsers' => [],
            'blockedBrowsers' => [],
            'allowedPlatforms' => [],
            'blockedPlatforms' => [],
            'allowedDevices' => [],
            'blockedDevices' => [],
            'allowedMimeTypes' => [],
            'blockedMimeTypes' => [],
            'blockedExtensions' => [],
            'xssPatterns' => [
                '#(<[^>]+[\x00-\x20\"\'\/])(form|formaction|on\w*|style|xmlns|xlink:href)[^>]*>?#iUu',
                '!((java|live|vb)script|mocha|feed|data):(\w)*!iUu',
                '#-moz-binding[\x00-\x20]*:#u',
                '#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base|img)[^>]*>?#i'
            ],
            'sqlPatterns' => [
                '#[\d\W](union select|union join|union distinct)[\d\W]#is',
                '#[\d\W](union|union select|insert|from|where|concat|into|cast|truncate|select|delete|having)[\d\W]#is'
            ],
        ];

        $this->assertSame($expectedConfig, $config);
    }
}
