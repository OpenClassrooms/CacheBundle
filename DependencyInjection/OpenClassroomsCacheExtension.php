<?php

namespace OpenClassrooms\Bundle\CacheBundle\DependencyInjection;

use OpenClassrooms\Cache\CacheProvider\CacheProviderType;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class OpenClassroomsCacheExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array            $config    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $config);

        $provider = $config['provider'];
        $container->setParameter(
            'openclassrooms.cache.default_lifetime',
            $config['default_lifetime']
        );

        $container->setParameter(
            'openclassrooms.cache.cache_provider_type',
            key($provider)
        );

        switch (key($provider)) {
            case CacheProviderType::REDIS:
                $providerConfig = $provider[CacheProviderType::REDIS];
                $container->setParameter('openclassrooms.cache.provider_host', $providerConfig['host']);
                $container->setParameter('openclassrooms.cache.provider_port', $providerConfig['port']);
                $container->setParameter('openclassrooms.cache.provider_timeout', $providerConfig['timeout']);
                break;
            default;
                break;
        }

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config/'));
        $loader->load('services.xml');
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return 'open_classrooms_cache';
    }

}
