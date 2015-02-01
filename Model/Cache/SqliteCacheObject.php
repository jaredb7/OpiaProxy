<?php
App::uses('ProxyCache', 'OpiaProxy.Model/Cache');

class SqliteCacheObject extends ProxyCache implements ICacheStorage
{
    /**
     * @var SQLiteDatabase
     */
    private $_db;
    private $_db_location;
    private $_db_name = "proxy_cache.db";

    public function __construct()
    {
        parent::__construct();

        $this->_db_location = App::pluginPath('OpiaProxy') . "tmp" . DS . "persist";

        if (file_exists($this->_db_location . DS . $this->_db_name)) {
            $this->_db = new SQLite3($this->_db_location . DS . $this->_db_name, SQLITE3_OPEN_READWRITE);
        } else {
            $this->_db = new SQLite3($this->_db_location . DS . $this->_db_name);

            $this->_db->exec(
                'CREATE TABLE "cache" (
                "id"  INTEGER NOT NULL,
                "request_hash"  TEXT(64) NOT NULL,
                "request_response"  BLOB,
                "created"  INTEGER,
                PRIMARY KEY ("id" ASC)
                );');
        }
    }

    /**
     * Checks if the file for the specified request exists
     *
     * @param $request
     * @return bool
     */
    public function exists($request)
    {
        $request_hash = $this->hash_request($request);

        $statement = $this->_db->prepare('SELECT id FROM cache WHERE request_hash = :hash;');
        $statement->bindValue(':hash', $request_hash);
        $result = $statement->execute();

        $results = $result->fetchArray(SQLITE3_ASSOC);

        if (!empty($results)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Reads the database entry
     *
     * @param array|string $request
     * @return bool|null|string
     */
    public function read($request)
    {
        $request_hash = $this->hash_request($request);

        $statement = $this->_db->prepare('SELECT * FROM cache WHERE request_hash = :hash;');
        $statement->bindValue(':hash', $request_hash);
        $result = $statement->execute();

        $results = $result->fetchArray(SQLITE3_ASSOC);

        if (!empty($results)) {
            return $results['request_response'];
        } else {
            return true;
        }
    }

    /**
     * Writes the request data ($result) into the database
     *
     * @param string $request
     * @param mixed $result
     * @return bool
     */
    public function save($request, $result)
    {
        $request_hash = $this->hash_request($request);

        $statement = $this->_db->prepare('INSERT INTO cache (request_hash, request_response, created) VALUES(:hash, :response, :created);');
        $statement->bindValue(':hash', $request_hash);
        $statement->bindValue(':response', $result);
        $statement->bindValue(':created', time());

        $result = $statement->execute();

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Removes the specified key/request_hash from the cache
     * @param array|string $key
     * @return array|bool
     */
    public function del($key)
    {
        //not required, so just return true;
        return true;
    }

    /**
     * Removes old cache entries by checking the created time
     * @return bool|void
     */
    public function del_old()
    {
        //Get all the results from the db
        $statement = $this->_db->prepare('DELETE FROM cache WHERE created < :ttl_expiry;');

        //Gives us the latest time that a entry can still be valid on.
        //anything before this is earlier and therefore older
        $ttl_expiry = time() - $this->_cacheTTL;

        $statement->bindValue(':ttl_expiry', $ttl_expiry);
        $result = $statement->execute();

        if ($result) {
            $message = "Finished deleting old cache entries. Deleted (" . $this->_db->changes() . ") entries.";
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
        //Get all the results from the db
        $statement = $this->_db->prepare('DELETE FROM cache WHERE 1=1;');
        $result = $statement->execute();

        if ($result) {
            $message = "PURGE: Finished deleting old cache entries. Deleted (" . $this->_db->changes() . ") entries.";
            CakeLog::write('info', $message, 'opia-proxy');

            return $message;
        } else {
            $message = "PURGE: Something went wrong.";
            CakeLog::write('warning', $message, 'opia-proxy');

            return $message;
        }
    }

    /**
     * Returns a list of items in the cache
     * @return mixed|void
     */
    public function list_cache()
    {
        $statement = $this->_db->prepare('SELECT * FROM cache WHERE 1=1;');
        $result = $statement->execute();
//        $results = $result->fetchArray(SQLITE3_ASSOC);

        $ttl_expiry = time() - $this->_cacheTTL;
        $return_list = array();

        while ($results = $result->fetchArray(SQLITE3_ASSOC)) {
            $expired = false;
            $ttl = $results['created'];

            if ($ttl < $ttl_expiry) {
                $expired = true;
            }

            $return_list[] = array(
                'item_name' => $results['request_hash'],
                'contents' => $results['request_response'],
                'location' => $results['request_hash'],
                'ttl' => $ttl,
                'expired' => $expired
            );

        }

        return $return_list;
    }

}