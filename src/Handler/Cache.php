<?php


namespace PeterVanDommelen\Parser\Handler;

/**
 * Very basic cache with objects as keys
 */
class Cache
{
    private $values = array();
    private $keys = array();

    public function has($key) {
        $identifier = spl_object_hash($key);
        return isset($this->keys[$identifier]);
    }

    public function get($key) {
        $identifier = spl_object_hash($key);
        return $this->values[$identifier];
    }

    public function set($key, $value) {
        $identifier = spl_object_hash($key);
        if (isset($this->keys[$identifier]) === false) {
            $this->keys[$identifier] = $key;
        }
        $this->values[$identifier] = &$value;
    }
}