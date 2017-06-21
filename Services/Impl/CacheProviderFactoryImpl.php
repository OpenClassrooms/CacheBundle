<?php

namespace OpenClassrooms\Bundle\CacheBundle\Services\Impl;

use Doctrine\Common\Cache\CacheProvider;
use OpenClassrooms\Bundle\CacheBundle\Services\CacheProviderFactory;
use OpenClassrooms\Cache\CacheProvider\CacheProviderType;
use Symfony\Component\DependencyInjection\Container;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CacheProviderFactoryImpl implements CacheProviderFactory
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @return CacheProvider
     */
    public function create()
    {
        $cacheProviderBuilder = $this->container->get('openclassrooms.cache.cache_provider_builder');

        switch ($this->container->getParameter('openclassrooms.cache.cache_provider_type')) {
            case CacheProviderType::REDIS:
                $cacheProvider = $cacheProviderBuilder
                    ->create(CacheProviderType::REDIS)
                    ->withHost($this->container->getParameter('openclassrooms.cache.provider_host'))
                    ->withPort($this->container->getParameter('openclassrooms.cache.provider_port'))
                    ->withTimeout($this->container->getParameter('openclassrooms.cache.provider_timeout'))
                    ->build();
                break;
            case CacheProviderType::ARRAY_CACHE:
            default:
                $cacheProvider = $cacheProviderBuilder
                    ->create(CacheProviderType::ARRAY_CACHE)
                    ->build();
        }

        return $cacheProvider;
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }
}
