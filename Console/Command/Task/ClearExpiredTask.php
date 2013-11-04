<?php
App::uses('AppShell', 'Console/Command');
App::uses('ProxyCache', 'OpiaProxy.Model/Cache');

/**
 * Console class to removing old entries from the cache
 *
 * @package      OpiaProxy.Console.Command
 */
class ClearExpiredTask extends AppShell
{
    /**
     * Main entry point
     *
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $this->proc();
    }

    /**
     * Default method called by CakeResque workers
     * @return bool
     * @throws Exception
     */
    public function proc()
    {
        $cache_persist_type = Configure::read('OpiaProxy.perist_type');


        if ($cache_persist_type == 'sqlite') {
            App::uses('SqliteCacheObject', 'OpiaProxy.Model/Cache');
            $this->_proxy_client = new SqliteCacheObject();
            $this->_proxy_client->del_old();
        } else if ($cache_persist_type == 'mysql') {
            App::uses('MysqlCacheObject', 'OpiaProxy.Model/Cache');
            $this->_proxy_client = new MysqlCacheObject();
            $this->_proxy_client->del_old();
        } else {
            App::uses('FileCacheObject', 'OpiaProxy.Model/Cache');
            $this->_proxy_client = new FileCacheObject();
            $this->_proxy_client->del_old();
        }
    }

    /**
     * get the option parser.
     *
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->description(array('Clears expired cache entries from the persistent storage.',));
        //configure parser
        return $parser;
    }
}