<?php

namespace OpenClassrooms\Bundle\CacheBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('openclassrooms_cache');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('provider')
                ->performNoDeepMerging()
                ->beforeNormalization()
                    ->ifString()
                    ->then(function($v) { return array($v => array()); })
                ->end()
                    ->children()
                        ->append($this->addArrayNode())
                        ->append($this->addRedisNode())
                    ->end()
                ->end()
            ->end()
            ->children()
                ->scalarNode('default_lifetime')
                    ->defaultValue(0)
                ->end()
            ->end();

        return $treeBuilder;
    }

    private function addArrayNode(): NodeDefinition
    {
        return (new TreeBuilder('array'))->getRootNode();
    }

    private function addRedisNode(): NodeDefinition
    {
        $nodeBuilder = new TreeBuilder('redis');
        $node = $nodeBuilder->getRootNode();

        $node
            ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('connection_id')->defaultNull()->end()
                    ->scalarNode('host')->end()
                    ->scalarNode('port')->defaultValue(6379)->end()
                    ->scalarNode('timeout')->defaultValue(0.0)->end()
                ->end();

        return $node;
    }
}
