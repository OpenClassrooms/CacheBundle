<?php

namespace OpenClassrooms\Bundle\CacheBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('openclassrooms_cache');
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
                        ->append($this->addMemcacheNode())
                        ->append($this->addMemcachedNode())
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

    /**
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    private function addArrayNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('array');

        return $node;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    private function addMemcacheNode()
    {
        $nodeBuilder = new TreeBuilder();
        $node = $nodeBuilder->root('memcache');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('host')->end()
                ->scalarNode('port')->defaultValue(11211)->end()
                ->scalarNode('timeout')->defaultValue(0)->end()
            ->end();

        return $node;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    private function addMemcachedNode()
    {
        $nodeBuilder = new TreeBuilder();
        $node = $nodeBuilder->root('memcached');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('host')->end()
                ->scalarNode('port')->defaultValue(1121)->end()
            ->end();

        return $node;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    private function addRedisNode()
    {
        $nodeBuilder = new TreeBuilder();
        $node = $nodeBuilder->root('redis');

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
