<?php

namespace CommunityDS\Deputy\Api\Adapter;

interface CacheInterface
{
    /**
     * Returns a value from the cache, or the default if not found.
     *
     * @param string $key Cache key
     * @param mixed $default Default value if not found
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Sets the value within the cache.
     *
     * @param string $key Cache key
     * @param mixed $value Value to store
     *
     * @return void
     */
    public function set($key, $value);

    /**
     * Removes a value from the cache and returns the value if found.
     *
     * @param string $key Cache key
     * @param mixed $default Default value if not found
     *
     * @return mixed
     */
    public function remove($key, $default = null);
}
