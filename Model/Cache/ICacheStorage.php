<?php
interface ICacheStorage
{
    /**
     * Lists all the items currently in the cache
     * @return mixed
     */
    public function list_cache();

    /**
     * Save variable in memory storage
     *
     * @param string $key - key
     * @param mixed $value - value
     * @return boolean Return true on success, or false on error
     */
    public function save($key, $value);

    /**
     * Read data from memory storage
     *
     * @param string|array $key (string or array of string keys)
     * @return boolean|string Return false on error, or a string if key was read.
     */
    public function read($key);

    /**
     * Checks if the specified key exists
     * @param $key
     * @return boolean Return true if key exists, false if not
     */
    public function exists($key);

    /**
     * Delete key or array of keys from storage
     * @param string|array $key - keys
     * @return boolean|array - if array of keys was passed, on error will be returned array of not deleted keys, or 'true' on success.
     */
    public function del($key);

    /**
     * Delete old (by ttl) variables from storage
     * @return boolean
     */
    public function del_old();

    /**
     * Purges the entire cache
     * @return boolean Return true on success, or false on error
     */
    public function purge();
}