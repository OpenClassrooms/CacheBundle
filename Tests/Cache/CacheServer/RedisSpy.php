<?php

namespace OpenClassrooms\Bundle\CacheBundle\Tests\Cache\CacheServer;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class RedisSpy extends \Redis
{
    /**
     * @var string
     */
    public $host;

    /**
     * @var int
     */
    public $port;

    /**
     * @var float
     */
    public $timeout;

    public function connect($host, $port = 6379, $timeout = 0.0, $retry_interval = null)
    {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;

        return true;
    }

    public function setOption($name, $value)
    {
        return true;
    }
}
