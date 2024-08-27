<?php

namespace Lemric\WAF\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('waf');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('allowedOrigins')
                    ->info('List of allowed origins for cross-origin requests')
                    ->scalarPrototype()->end()
                    ->defaultValue([])
                ->end()

                ->arrayNode('blockedIps')
                    ->info('List of blocked IP addresses')
                    ->scalarPrototype()->end()
                    ->defaultValue([])
                ->end()

                ->arrayNode('allowedIps')
                    ->info('List of allowed IP addresses')
                    ->scalarPrototype()->end()
                    ->defaultValue([])
                ->end()

                ->arrayNode('blockedPaths')
                    ->info('List of blocked URL paths')
                    ->scalarPrototype()->end()
                    ->defaultValue([])
                ->end()

                ->arrayNode('allowedBrowsers')
                    ->info('List of allowed browsers')
                    ->scalarPrototype()->end()
                    ->defaultValue([])
                ->end()

                ->arrayNode('blockedBrowsers')
                    ->info('List of blocked browsers')
                    ->scalarPrototype()->end()
                    ->defaultValue([])
                ->end()

                ->arrayNode('allowedPlatforms')
                    ->info('List of allowed platforms')
                    ->scalarPrototype()->end()
                    ->defaultValue([])
                ->end()

                ->arrayNode('blockedPlatforms')
                    ->info('List of blocked platforms')
                    ->scalarPrototype()->end()
                    ->defaultValue([])
                ->end()

                ->arrayNode('allowedDevices')
                    ->info('List of allowed device types')
                    ->scalarPrototype()->end()
                    ->defaultValue([])
                ->end()

                ->arrayNode('blockedDevices')
                    ->info('List of blocked device types')
                    ->scalarPrototype()->end()
                    ->defaultValue([])
                ->end()

                ->arrayNode('xssPatterns')
                    ->info('Patterns for detecting XSS attacks')
                    ->scalarPrototype()->end()
                    ->defaultValue([
                        '#(<[^>]+[\x00-\x20\"\'\/])(form|formaction|on\w*|style|xmlns|xlink:href)[^>]*>?#iUu',
                        '!((java|live|vb)script|mocha|feed|data):(\w)*!iUu',
                        '#-moz-binding[\x00-\x20]*:#u',
                        '#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base|img)[^>]*>?#i'
                    ])
                ->end()

                ->arrayNode('sqlPatterns')
                    ->info('Patterns for detecting SQL Injection attacks')
                    ->scalarPrototype()->end()
                    ->defaultValue([
                        '#[\d\W](union select|union join|union distinct)[\d\W]#is',
                        '#[\d\W](union|union select|insert|from|where|concat|into|cast|truncate|select|delete|having)[\d\W]#is'
                    ])
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

