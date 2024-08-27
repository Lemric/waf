<?php

namespace Lemric\WAF\Tests\DependencyInjection;

use Lemric\WAF\DependencyInjection\LemricWAFExtension;
use Lemric\WAF\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

class LemricWAFExtensionTest extends TestCase
{
    public function testLoadConfiguration()
    {
        $extension = new LemricWAFExtension();
        $container = new ContainerBuilder();

        $config = [
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
            'allowedOrigins' => ['https://example.com'],
        ];

        $extension->load([$config], $container);

        $this->assertTrue($container->hasParameter('waf.blockedIps'));
        $this->assertEquals($config['blockedIps'], $container->getParameter('waf.blockedIps'));

        $this->assertTrue($container->hasParameter('waf.allowedIps'));
        $this->assertEquals($config['allowedIps'], $container->getParameter('waf.allowedIps'));

        $this->assertTrue($container->hasParameter('waf.blockedPaths'));
        $this->assertEquals($config['blockedPaths'], $container->getParameter('waf.blockedPaths'));

        $this->assertTrue($container->hasParameter('waf.allowedBrowsers'));
        $this->assertEquals($config['allowedBrowsers'], $container->getParameter('waf.allowedBrowsers'));

        $this->assertTrue($container->hasParameter('waf.blockedBrowsers'));
        $this->assertEquals($config['blockedBrowsers'], $container->getParameter('waf.blockedBrowsers'));

        $this->assertTrue($container->hasParameter('waf.allowedPlatforms'));
        $this->assertEquals($config['allowedPlatforms'], $container->getParameter('waf.allowedPlatforms'));

        $this->assertTrue($container->hasParameter('waf.blockedPlatforms'));
        $this->assertEquals($config['blockedPlatforms'], $container->getParameter('waf.blockedPlatforms'));

        $this->assertTrue($container->hasParameter('waf.allowedDevices'));
        $this->assertEquals($config['allowedDevices'], $container->getParameter('waf.allowedDevices'));

        $this->assertTrue($container->hasParameter('waf.blockedDevices'));
        $this->assertEquals($config['blockedDevices'], $container->getParameter('waf.blockedDevices'));

        $this->assertTrue($container->hasParameter('waf.xssPatterns'));
        $this->assertEquals($config['xssPatterns'], $container->getParameter('waf.xssPatterns'));

        $this->assertTrue($container->hasParameter('waf.sqlPatterns'));
        $this->assertEquals($config['sqlPatterns'], $container->getParameter('waf.sqlPatterns'));

        $this->assertTrue($container->hasParameter('waf.allowedOrigins'));
        $this->assertEquals($config['allowedOrigins'], $container->getParameter('waf.allowedOrigins'));
    }

    public function testDefaultConfiguration()
    {
        $extension = new LemricWAFExtension();
        $container = new ContainerBuilder();

        $extension->load([], $container);

        $this->assertTrue($container->hasParameter('waf.blockedIps'));
        $this->assertEquals([], $container->getParameter('waf.blockedIps'));

        $this->assertTrue($container->hasParameter('waf.allowedIps'));
        $this->assertEquals([], $container->getParameter('waf.allowedIps'));

        $this->assertTrue($container->hasParameter('waf.blockedPaths'));
        $this->assertEquals([], $container->getParameter('waf.blockedPaths'));

        $this->assertTrue($container->hasParameter('waf.allowedBrowsers'));
        $this->assertEquals([], $container->getParameter('waf.allowedBrowsers'));

        $this->assertTrue($container->hasParameter('waf.blockedBrowsers'));
        $this->assertEquals([], $container->getParameter('waf.blockedBrowsers'));

        $this->assertTrue($container->hasParameter('waf.allowedPlatforms'));
        $this->assertEquals([], $container->getParameter('waf.allowedPlatforms'));

        $this->assertTrue($container->hasParameter('waf.blockedPlatforms'));
        $this->assertEquals([], $container->getParameter('waf.blockedPlatforms'));

        $this->assertTrue($container->hasParameter('waf.allowedDevices'));
        $this->assertEquals([], $container->getParameter('waf.allowedDevices'));

        $this->assertTrue($container->hasParameter('waf.blockedDevices'));
        $this->assertEquals([], $container->getParameter('waf.blockedDevices'));

        $this->assertTrue($container->hasParameter('waf.xssPatterns'));
        $this->assertEquals([
            '#(<[^>]+[\x00-\x20\"\'\/])(form|formaction|on\w*|style|xmlns|xlink:href)[^>]*>?#iUu',
            '!((java|live|vb)script|mocha|feed|data):(\w)*!iUu',
            '#-moz-binding[\x00-\x20]*:#u',
            '#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base|img)[^>]*>?#i'
        ], $container->getParameter('waf.xssPatterns'));

        $this->assertTrue($container->hasParameter('waf.sqlPatterns'));
        $this->assertEquals([
            '#[\d\W](union select|union join|union distinct)[\d\W]#is',
            '#[\d\W](union|union select|insert|from|where|concat|into|cast|truncate|select|delete|having)[\d\W]#is'
        ], $container->getParameter('waf.sqlPatterns'));

        $this->assertTrue($container->hasParameter('waf.allowedOrigins'));
        $this->assertEquals([], $container->getParameter('waf.allowedOrigins'));
    }
}
