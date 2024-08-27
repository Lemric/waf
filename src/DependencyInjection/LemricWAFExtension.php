<?php

namespace Lemric\WAF\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class LemricWAFExtension extends Extension
{

    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');

        $container->setParameter('waf.blockedIps', $config['blockedIps']);
        $container->setParameter('waf.allowedIps', $config['allowedIps']);
        $container->setParameter('waf.blockedPaths', $config['blockedPaths']);
        $container->setParameter('waf.allowedBrowsers', $config['allowedBrowsers']);
        $container->setParameter('waf.blockedBrowsers', $config['blockedBrowsers']);
        $container->setParameter('waf.allowedPlatforms', $config['allowedPlatforms']);
        $container->setParameter('waf.blockedPlatforms', $config['blockedPlatforms']);
        $container->setParameter('waf.allowedDevices', $config['allowedDevices']);
        $container->setParameter('waf.blockedDevices', $config['blockedDevices']);
        $container->setParameter('waf.xssPatterns', $config['xssPatterns']);
        $container->setParameter('waf.sqlPatterns', $config['sqlPatterns']);
        $container->setParameter('waf.allowedOrigins', $config['allowedOrigins']);
        $container->setParameter('waf.allowedMimeTypes', $config['allowedMimeTypes']);
        $container->setParameter('waf.blockedMimeTypes', $config['blockedMimeTypes']);
        $container->setParameter('waf.blockedExtensions', $config['blockedExtensions']);
    }
}