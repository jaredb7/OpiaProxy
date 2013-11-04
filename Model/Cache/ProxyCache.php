<?php
App::uses('ICacheStorage', 'OpiaProxy.Model/Cache');

abstract class ProxyCache implements ICacheStorage
{

    protected $_entity;
    public $_cacheTTL;

    public function __construct()
    {
        //Work out TTL for this entity
        $this->_cacheTTL = 86400;
    }

    /**
     * Generates a MD5 hash of the request params
     * @param $request
     * @return string
     */
    public function hash_request($request)
    {
        return md5(json_encode($request));
    }
}