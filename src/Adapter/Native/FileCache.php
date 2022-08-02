<?php

namespace CommunityDS\Deputy\Api\Adapter\Native;

use CommunityDS\Deputy\Api\Adapter\CacheInterface;
use CommunityDS\Deputy\Api\Component;

/**
 * Stores serialized content to a specific file in the file system.
 */
class FileCache extends RuntimeCache
{
    /**
     * Path to cache file.
     *
     * @var string
     */
    public $file;

    public function init()
    {
        parent::init();
        if (file_exists($this->file)) {
            $content = file_get_contents($this->file);
            if ($content) {
                $this->data = unserialize($content);
            }
        }
    }

    /**
     * Saves the entire cache to the file.
     *
     * @return void
     */
    protected function commit()
    {
        $success = file_put_contents(
            $this->file,
            serialize($this->data)
        );
    }

    public function set($key, $value)
    {
        $retval = parent::set($key, $value);
        $this->commit();
        return $retval;
    }

    public function remove($key, $default = null)
    {
        $retval = parent::remove($key, $default);
        $this->commit();
        return $retval;
    }
}
