<?php

namespace OpenClassrooms\Bundle\CacheBundle\Tests\DependencyInjection;

use OpenClassrooms\Bundle\CacheBundle\DependencyInjection\OpenClassroomsCacheExtension;
use OpenClassrooms\Bundle\CacheBundle\OpenClassroomsCacheBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class YamlOpenClassroomsCacheExtensionTest extends TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var Extension
     */
    private $extension;

    /**
     * @var YamlFileLoader
     */
    private $loader;

    /**
     * test
     */
    public function NoConfiguration_ContainerHasArrayCache()
    {
        $this->container->compile();

        $cacheProvider = $this->container->get('openclassrooms.cache.cache_provider');
        $cache = $this->container->get('openclassrooms.cache.cache');

        $this->assertInstanceOf('Doctrine\Common\Cache\ArrayCache', $cacheProvider);
        $this->assertAttributeInstanceOf('Doctrine\Common\Cache\ArrayCache', 'cache', $cache);
        $this->assertAttributeEquals(0, 'defaultLifetime', $cache);
    }

    /**
     * @test
     */
    public function DefaultLifetime_CacheHasDefaultLifeTime()
    {
        $this->loader->load('DefaultLifetimeConfig.yml');
        $this->container->compile();

        $cache = $this->container->get('openclassrooms.cache.cache');

        $this->assertAttributeEquals(10, 'defaultLifetime', $cache);
    }

    /**
     * @test
     */
    public function Array_ContainerHasArrayCache()
    {
        $this->loader->load('ArrayConfig.yml');
        $this->container->compile();

        $cacheProvider = $this->container->get('openclassrooms.cache.cache_provider');
        $cache = $this->container->get('openclassrooms.cache.cache');

        $this->assertInstanceOf('Doctrine\Common\Cache\ArrayCache', $cacheProvider);
        $this->assertAttributeInstanceOf('Doctrine\Common\Cache\ArrayCache', 'cache', $cache);
    }

    /**
     * @test
     */
    public function RedisConfiguration_ContainerHasRedisCache()
    {
        $this->loader->load('RedisConfig.yml');
        $this->container->compile();

        $cacheProvider = $this->container->get('openclassrooms.cache.cache_provider');
        $cache = $this->container->get('openclassrooms.cache.cache');

        $this->assertInstanceOf('Doctrine\Common\Cache\RedisCache', $cacheProvider);
        $this->assertAttributeInstanceOf('Doctrine\Common\Cache\RedisCache', 'cache', $cache);
    }

    protected function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new OpenClassroomsCacheExtension();
        $this->container->registerExtension($this->extension);
        $this->container->loadFromExtension('open_classrooms_cache');

        $bundle = new OpenClassroomsCacheBundle();
        $bundle->build($this->container);

        $this->container->setParameter(
            'openclassrooms.cache.cache_provider_builder.class',
            'OpenClassrooms\Bundle\CacheBundle\Tests\Cache\CacheProviderBuilderMock'
        );
        $this->loader = new YamlFileLoader($this->container, new FileLocator(__DIR__ . '/Fixtures/Yaml/'));
    }
}
