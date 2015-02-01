<?php
App::uses('MysqlCacheModel', 'OpiaProxy.Model/Cache');
App::uses('ProxyCache', 'OpiaProxy.Model/Cache');

class MysqlCacheObject extends ProxyCache implements ICacheStorage
{
    private $_db;

    public function __construct()
    {
        parent::__construct();
        $this->_db = new MysqlCacheModel();
    }

    /**
     * Checks if the file for the specified request exiss
     *
     * @param $request
     * @return bool
     */
    public function exists($request)
    {
        $request_hash = $this->hash_request($request);
        $result = $this->_db->find('first', array('conditions' => array('request_hash' => $request_hash), 'fields' => array('id')));

        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Reads the request file
     *
     * @param array|string $request
     * @return bool|null|string
     */
    public function read($request)
    {
        $request_hash = $this->hash_request($request);
        $result = $this->_db->find('first', array('conditions' => array('request_hash' => $request_hash), 'fields' => array('request_response')));

        if (!empty($result)) {
            return $result['MysqlCacheModel']['request_response'];
        } else {
            return false;
        }
    }

    /**
     * Writes the request data ($result) into the request file
     *
     * @param string $request
     * @param mixed $result
     * @return bool
     */
    public function save($request, $result)
    {
        $request_hash = $this->hash_request($request);
        $data = array(
            'request_hash' => $request_hash,
            'request_response' => $result,
            'created' => time());

        if ($this->_db->save($data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Removes the specified filename from the file cache
     * @param array|string $key
     * @return array|bool
     */
    public function del($key)
    {
        //Not required so just return true
        return true;
    }

    /**
     * Removes old cache entries by checking the file modification time of all times in the cache directory
     * @return bool|void
     */
    public function del_old()
    {
        $ttl_expiry = time() - $this->_cacheTTL;

        $result = $this->_db->deleteAll(array('created <' => $ttl_expiry));

        if ($result) {
            $message = "Finished deleting old cache entries. Deleted (" . $this->_db->getAffectedRows() . ") entries.";
            CakeLog::write('info', $message, 'opia-proxy');

            return $message;
        } else {
            $message = "Something went wrong.";
            CakeLog::write('warning', $message, 'opia-proxy');

            return $message;
        }
    }

    /**
     * Purges the entire cache
     * @return bool|void
     */
    public function purge()
    {
        $result = $this->_db->deleteAll(array('created >' => -1));

        if ($result) {
            $message = "PURGE: Finished deleting old cache entries. Deleted (" . $this->_db->getAffectedRows() . ") entries.";
            CakeLog::write('info', $message, 'opia-proxy');

            return $message;
        } else {
            $message = "PURGE: Something went wrong.";
            CakeLog::write('warning', $message, 'opia-proxy');

            return $message;
        }
    }

    /**
     * Returns a list of all the items in the cache
     * @return mixed|void
     */
    public function list_cache()
    {
        $result = $this->_db->find('all');

        $return_list = array();
        $ttl_expiry = time() - $this->_cacheTTL;

        foreach ($result as $i => $cache_item) {
            $expired = false;
            $ttl = $cache_item[$this->_db->name]['created'];

            if ($ttl < $ttl_expiry) {
                $expired = true;
            }

            $return_list[] = array(
                'item_name' => $cache_item[$this->_db->name]['request_hash'],
                'contents' =>  $cache_item[$this->_db->name]['request_response'],
                'location' => $cache_item[$this->_db->name]['request_hash'],
                'ttl' => $ttl,
                'expired' => $expired
            );
        }

        return $return_list;
    }

}