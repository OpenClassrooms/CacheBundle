[![Build Status](https://travis-ci.org/OpenClassrooms/CacheBundle.svg?branch=master)](https://travis-ci.org/OpenClassrooms/CacheBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b04e23bf-8e36-4704-801e-bb29a7719ed3/mini.png)](https://insight.sensiolabs.com/projects/b04e23bf-8e36-4704-801e-bb29a7719ed3)
[![Coverage Status](https://coveralls.io/repos/OpenClassrooms/CacheBundle/badge.png?branch=master)](https://coveralls.io/r/OpenClassrooms/CacheBundle?branch=master)

CacheBundle adds features to Doctrine Cache implementation
- Default lifetime
- Fetch with a namespace
- Save with a namespace
- Cache invalidation through namespace strategy
- CacheProvider Builder

See [OpenClassrooms/Cache](https://github.com/OpenClassrooms/Cache) for more details.

## Installation
This bundle can be installed using composer:

```composer require jms/serializer-bundle```
or by adding the package to the composer.json file directly.

```json
{
    "require": {
        "openclassrooms/cache-bundle": "*"
    }
}
```

After the package has been installed, add the bundle to the AppKernel.php file:

```php
// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new OpenClassrooms\Bundle\CacheBundle\OpenClassroomsCacheBundle(),
    // ...
);
```

## Configuration
```yaml
open_classrooms_cache:
    default_lifetime: 10    (optional, default = 0)
# Providers
    # array
    provider: array
    # redis            
    provider:
        redis:
            host: localhost
            port: 6379      (optional, default = 6379)
            timeout: 0.0    (optional, default = 0.0)
```
## Usage
The configured cache is available as ```openclassrooms.cache.cache``` service:
```php
$cache = $container->get('openclassrooms.cache.cache');

$cache->fetch($id);
$cache->fetchWithNamespace($id, $namespaceId);
$cache->save($id, $data);
$cache->saveWithNamespace($id, $data, $namespaceId);
$cache->invalidate($namespaceId);

```

The configured cache provider is available as ```openclassrooms.cache.cache_provider``` service:
```php
$cacheProvider = $container->get('openclassrooms.cache.cache_provider');
```

The cache provider builder is available as ```openclassrooms.cache.cache_provider``` service:
```php
$builder = $container->get('openclassrooms.cache.cache_provider_builder');

// Redis
$cacheProvider = $builder
    ->create(CacheProviderType::REDIS)
    ->withHost('127.0.0.1')
    ->withPort(6379) // Default 6379
    ->withTimeout(0.0) // Default 0.0
    ->build();
```

See [OpenClassrooms/Cache](https://github.com/OpenClassrooms/Cache) for more details.


