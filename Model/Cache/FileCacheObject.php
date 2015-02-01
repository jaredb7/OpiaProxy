<?php
App::uses('Folder', 'Utility');

App::uses('ProxyCache', 'OpiaProxy.Model/Cache');

class FileCacheObject extends ProxyCache implements ICacheStorage
{

    private $cache_file_location;
    private $cache_file_extension = ".cache";
    private $cahe_file_directory = 'cache';

    public function __construct()
    {
        parent::__construct();

        $this->cache_file_location = App::pluginPath('OpiaProxy') . "tmp" . DS . $this->cahe_file_directory;
    }

    /**
     * Checks if the file for the specified request exiss
     *
     * @param $request
     * @return bool
     */
    public function exists($request)
    {
        return file_exists($this->generate_filename($request));
    }

    /**
     * Reads the request file
     *
     * @param array|string $request
     * @return bool|null|string
     */
    public function read($request)
    {
        if ($this->exists($request)) {
            $cache_file = $this->generate_filename($request);

            //check age just in case
            $file_mod_time = filemtime($cache_file);
            //If the file is still valid
            if (abs(time() - $file_mod_time) <= $this->_cacheTTL) {
                return file_get_contents($cache_file);
            }
            return false;
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
        //Get hash for request
        $cache_file = $this->generate_filename($request);

        if (file_put_contents($cache_file, $result) === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Removes the specified filename from the file cache
     * @param array|string $file_name
     * @return array|bool
     */
    public function del($file_name)
    {
        if (unlink($this->cache_file_location . DS . $file_name)) {
            CakeLog::write('info', "Removed file from cache: " . $this->cache_file_location . DS . $file_name, 'opia-proxy');
            return true;
        } else {
            CakeLog::write('info', "Could not remove file from cache: " . $this->cache_file_location . DS . $file_name, 'opia-proxy');
            return false;
        }
    }

    /**
     * Removes old cache entries by checking the file modification time of all times in the cache directory
     * @return bool|void
     */
    public function del_old()
    {
        //look at all the files in the cache directory and delete ones older than ttl age
        $dir = new Folder($this->cache_file_location);
        $files = $dir->find('.*' . $this->cache_file_extension);
        $non_deleted = array();
        $deleted = 0;
        foreach ($files as $file) {
            $file_mod_time = filemtime($this->cache_file_location . DS . $file);
            //If the file delta between current time and the file modification time is greater than the ttl, then remove it
            if (abs(time() - $file_mod_time) > $this->_cacheTTL) {
                if (!$this->del($file)) {
                    $non_deleted[] = $file;
                } else {
                    $deleted++;
                }
            }
        }
        if (empty($non_deleted)) {
            $message = "Finished deleting old cache entries. Deleted (" . $deleted . ") entries.";
            CakeLog::write('info', $message, 'opia-proxy');

            return $message;
        } else {
            $message = "Finished deleting old cache entries. ";
            $message .= "Failed to remove: " . json_encode($non_deleted);
            CakeLog::write('info', $message, 'opia-proxy');

            return $message;
        }
    }

    /**
     * Purges the entire cache
     * @return bool|void
     */
    public function purge()
    {
        //look at all the files in the cache directory and delete ones older than ttl age
        $dir = new Folder($this->cache_file_location);
        $files = $dir->find('.*' . $this->cache_file_extension);
        $non_deleted = array();
        $deleted = 0;
        foreach ($files as $file) {
            //If the file delta between current time and the file modification time is greater than the ttl, then remove it
            if (!$this->del($file)) {
                $non_deleted[] = $file;
            } else {
                $deleted++;
            }
        }
        if (empty($non_deleted)) {
            $message = "PURGE: Finished deleting old cache entries. Deleted (" . $deleted . ") entries.";
            CakeLog::write('info', $message, 'opia-proxy');

            return $message;
        } else {
            $message = "PURGE: Finished deleting old cache entries. ";
            $message .= "Failed to remove: " . json_encode($non_deleted);
            CakeLog::write('info', $message, 'opia-proxy');

            return $message;
        }
    }

    /**
     * Returns a list of all the items in the cache
     * @return mixed|void
     */
    public function list_cache()
    {
        //look at all the files in the cache directory and delete ones older than ttl age
        $dir = new Folder($this->cache_file_location);
        $files = $dir->find('.*' . $this->cache_file_extension);

        $return_list = array();

        foreach ($files as $file) {
            $expired = false;
            $file_mod_time = filemtime($this->cache_file_location . DS . $file);
            //If the file delta between current time and the file modification time is greater than the ttl, then remove it
            if (abs(time() - $file_mod_time) > $this->_cacheTTL) {
                $expired = true;
            }

            $return_list[] = array(
                'item_name' => $file,
                'contents' => file_get_contents($this->cache_file_location . DS . $file),
                'location' => $this->cache_file_location . DS . $file,
                'ttl' => abs(time() - $file_mod_time),
                'expired' => $expired
            );
        }
        return $return_list;
    }

    /**
     * Generates the filename for the supplied request
     *
     * @param $request
     * @return string
     */
    public function generate_filename($request)
    {
        $request_hash = $this->hash_request($request);
        return $this->cache_file_location . DS . $request_hash . $this->cache_file_extension;
    }
}