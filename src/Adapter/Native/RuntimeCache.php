<?php

namespace CommunityDS\Deputy\Api\Adapter\Native;

use CommunityDS\Deputy\Api\Adapter\CacheInterface;
use CommunityDS\Deputy\Api\Component;

/**
 * Stores the cache within memory, and clears the cache when this instance
 * is destroyed.
 */
class RuntimeCache extends Component implements CacheInterface
{

    /**
     * Stored values.
     *
     * @var array
     */
    protected $data = [];

    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        return $default;
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function remove($key, $default = null)
    {
        if (array_key_exists($key, $this->data)) {
            $value = $this->data[$key];
            unset($this->data[$key]);
            return $value;
        }
        return $default;
    }
}
