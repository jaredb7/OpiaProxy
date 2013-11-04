<?php
App::uses('HttpSocket', 'Network/Http');
App::uses('HttpSocketResponse', 'Network/Http');

/**
 * APIClient.php
 */

/**
 * Autoload the model definition files
 * @param string $className the class to attempt to load
 */
function swagger_autoloader($className)
{
    $currentDir = dirname(__FILE__);
    if (file_exists($currentDir . '/' . $className . '.php')) {
        include $currentDir . '/' . $className . '.php';
    } elseif (file_exists($currentDir . '/ApiModels/' . $className . '.php')) {
        include $currentDir . '/ApiModels/' . $className . '.php';
    }
}

spl_autoload_register('swagger_autoloader');

class APIClient
{
    public static $USER_AGENT = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:13.0) Gecko/20100101 Firefox/13.0.1";
    public static $POST = "POST";
    public static $GET = "GET";
    public static $PUT = "PUT";
    public static $DELETE = "DELETE";

    /**
     * Some variables for modifying caching
     */
    public $no_cache = false;
    public $cache_ttl = 0;

    /**
     * @var HttpSocket
     */
    private $_httpClient;

    /**
     * API Key?
     * @var
     */
    private $_apiKey;
    private $_apiServer;

    /**
     * @var ProxyCache
     */
    private $_proxy_client;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->_apiKey = Configure::read('OpiaProxy.api_key');
        $this->_apiServer = Configure::read('OpiaProxy.api_endpoint.Base');

        $this->cache_ttl = Configure::read('OpiaProxy.cache_ttl.data');

        $this->setup_user_agent();
        //Setup the proxy
        $this->setup_proxy_client();
        //Setup the http client
        $this->setup_http_socket();
    }

    /**
     * Setup a new user_agent string
     */
    public function setup_user_agent()
    {
        self::$USER_AGENT = "Mozilla/5.0 (CakeOpia/" . CAKE_OPIA_VER . " PHP/" . PHP_VERSION . " (CakeOpia))";
    }

    /**
     * Sets up the proxy client
     */
    public function setup_proxy_client()
    {
        $cache_persist_type = Configure::read('OpiaProxy.perist_type');

        if ($cache_persist_type == 'sqlite') {
            App::uses('SqliteCacheObject', 'OpiaProxy.Model/Cache');
            $this->_proxy_client = new SqliteCacheObject();
            $this->_proxy_client->_cacheTTL = $this->cache_ttl;
        } else if ($cache_persist_type == 'mysql') {
            App::uses('MysqlCacheObject', 'OpiaProxy.Model/Cache');
            $this->_proxy_client = new MysqlCacheObject();
            $this->_proxy_client->_cacheTTL = $this->cache_ttl;
        } else {
            App::uses('FileCacheObject', 'OpiaProxy.Model/Cache');
            $this->_proxy_client = new FileCacheObject();
            $this->_proxy_client->_cacheTTL = $this->cache_ttl;
        }
    }

    /**
     * Creates the HttpSocket, configure Auth and timeout
     */
    public function setup_http_socket()
    {
        $this->_httpClient = new HttpSocket(array('timeout' => 5));
        //Configure Authentication
        $this->_httpClient->configAuth('Basic',
            Configure::read('OpiaProxy.opiaLogin'),
            Configure::read('OpiaProxy.opiaPassword')
        );
    }

    /**
     * Makes call to the specified path/API
     * @param string $resourcePath path to method endpoint
     * @param string $method method to call
     * @param array $queryParams parameters to be place in query URL
     * @param array $postData parameters to be placed in POST body
     * @param array $headerParams parameters to be place in request header
     * @throws Exception
     * @return string
     */
    public function callAPI($resourcePath, $method, $queryParams, $postData, $headerParams)
    {
        //Final url is the base path + resource path(location|network|travel|version)
        $url = $this->_apiServer . $resourcePath;
        //Build query string
        if (!empty($queryParams)) {
            $url = ($url . '?' . http_build_query($queryParams));
        }

        //Parse the url
        $url_parse = parse_url($url);

        //Build Request array for HttpSocket
        $request = array(
            'method' => $method,
            'uri' => array(
                'scheme' => $url_parse['scheme'],
                'host' => $url_parse['host'],
                'path' => $url_parse['path'],
            ),
            'header' => array(
                'Host' => $url_parse['host'],
                'User-Agent' => self::$USER_AGENT,
                'Content-type' => 'application/json',
            ),
        );

        if (array_key_exists('query', $url_parse)) {
            $request['uri']['query'] = $url_parse['query'];
        }

        # Allow API key from $headerParams to override default
        $added_api_key = false;
        if ($headerParams != null) {
            foreach ($headerParams as $key => $val) {
                $request['header'][$key] = $val;
                if ($key == 'api_key') {
                    $added_api_key = true;
                }
            }
        }
        //Add default api key
        if (!$added_api_key) {
            $request['header']['api_key'] = $this->_apiKey;
        }
        //If we have post data,
        if (is_object($postData) || is_array($postData)) {
            $postData = json_encode(self::sanitizeForSerialization($postData));
            $request['body'] = $postData;
        }

        //TODO cache completion
        if (!$this->_proxy_client->exists($request)) {
            CakeLog::write('info', 'Fetching from OPIA API: ' . json_encode($url_parse), 'opia-proxy');

            $response = $this->_httpClient->request($request);
            //Save the request and result to the cache if the response is ok
            if ($response->code == 200 && $this->no_cache == false) {
                $this->_proxy_client->save($request, $response->body);
            }
        } else {
            CakeLog::write('info', 'Fetching from cache: HASH[' . $this->_proxy_client->hash_request($request) . '] :: REQUEST[' . json_encode($url_parse) . ']', 'opia-proxy');

            //Read the entry for this request
            $cache_data = $this->_proxy_client->read($request);

            //Build the http response object,
            $response = new HttpSocketResponse();
            $response->code = 200;
            $response->body = $cache_data;
        }

        // Handle the response
        if ($response->code == 0) {
            throw new Exception("TIMEOUT: API call to " . $url . " took more than 5s to return");
        } else if ($response->code == 200) {
            $data = json_decode($response->body);
        } else if ($response->code == 400) {
            throw new Exception(json_decode($response->body) . " " . $url . " : response code: " . $response->code);
        } else if ($response->code == 401) {
            throw new Exception("Unauthorized API request to " . $url . " : Invalid Login Credentials");
        } else if ($response->code == 403) {
            throw new Exception("Quota exceeded for this method, or a security error prevented completion of your (successfully authorized) request : " . $url);
        } else if ($response->code == 404) {
            $data = null;
        } else if ($response->code == 500) {
            throw new Exception("Internal server error, response code: " . $response->code);
        } else {
            throw new Exception("Can't connect to the api: " . $url . " : response code: " . $response->code);
        }

        return $data;
    }

//    public function callAPI_old(){
//        $headers = array();
//        $headers[] = "Content-type: application/json";
//
//        # Allow API key from $headerParams to override default
//        $added_api_key = False;
//        if ($headerParams != null) {
//            foreach ($headerParams as $key => $val) {
//                $headers[] = "$key: $val";
//                if ($key == 'api_key') {
//                    $added_api_key = True;
//                }
//            }
//        }
//        if (!$added_api_key) {
//            $headers[] = "api_key: " . $this->_apiKey;
//        }
//
//        if (is_object($postData) or is_array($postData)) {
//            $postData = json_encode(self::sanitizeForSerialization($postData));
//        }
//
//        $url = $this->_apiServer . $resourcePath;
//
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
//        // return the result on success, rather than just TRUE
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//
//        if (!empty($queryParams)) {
//            $url = ($url . '?' . http_build_query($queryParams));
//        }
//
//        if ($method == self::$POST) {
//            curl_setopt($curl, CURLOPT_POST, true);
//            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
//        } else if ($method == self::$PUT) {
//            $json_data = json_encode($postData);
//            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
//            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
//        } else if ($method == self::$DELETE) {
//            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
//            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
//        } else if ($method != self::$GET) {
//            throw new Exception('Method ' . $method . ' is not recognized.');
//        }
//        curl_setopt($curl, CURLOPT_URL, $url);
//
//        // Make the request
//        $response = curl_exec($curl);
//        $response_info = curl_getinfo($curl);
//        if ($response_info['http_code'] == 0) {
//            throw new Exception("TIMEOUT: api call to " . $url .
//            " took more than 5s to return");
//        } else if ($response_info['http_code'] == 200) {
//            $data = json_decode($response);
//        } else if ($response_info['http_code'] == 401) {
//            throw new Exception("Unauthorized API request to " . $url .
//            ": " . json_decode($response)->message);
//        } else if ($response_info['http_code'] == 404) {
//            $data = null;
//        } else {
//            throw new Exception("Can't connect to the api: " . $url .
//            " response code: " .
//            $response_info['http_code']);
//        }
//    }


    /**
     * Build a JSON POST object
     */
    public static function sanitizeForSerialization($postData)
    {
        foreach ($postData as $key => $value) {
            if (is_a($value, "DateTime")) {
                $postData->{$key} = $value->format(DateTime::ISO8601);
            }
        }
        return $postData;
    }

    /**
     * Take value and turn it into a string suitable for inclusion in
     * the path, by url-encoding.
     * @param string $value a string which will be part of the path
     * @return string the serialized object
     */
    public static function toPathValue($value)
    {
        return rawurlencode($value);
    }

    /**
     * Take value and turn it into a string suitable for inclusion in
     * the query, by imploding comma-separated if it's an object.
     * If it's a string, pass through unchanged. It will be url-encoded
     * later.
     * @param object $object an object to be serialized to a string
     * @return string the serialized object
     */
    public static function toQueryValue($object)
    {
        if (is_array($object)) {
            return implode(',', $object);
        } else {
            return $object;
        }
    }

    /**
     * Just pass through the header value for now. Placeholder in case we
     * find out we need to do something with header values.
     * @param string $value a string which will be part of the header
     * @return string the header string
     */
    public static function toHeaderValue($value)
    {
        return $value;
    }

    /**
     * Deserialize a JSON string into an object
     *
     * @param object $object object or primitive to be deserialized
     * @param string $class class name is passed as a string
     * @return object an instance of $class
     */
    public static function deserialize($object, $class)
    {

        if (gettype($object) == "NULL") {
            return $object;
        }

        if (substr($class, 0, 6) == 'array[') {
            $sub_class = substr($class, 6, -1);
            $sub_objects = array();
            foreach ($object as $sub_object) {
                $sub_objects[] = self::deserialize($sub_object, $sub_class);
            }
            return $sub_objects;
        } elseif ($class == 'DateTime') {
            //clean the input object
            $object = str_replace("/", "", str_replace("(", "", str_replace(")", "", str_replace("Date", "", $object))));

            if (substr_count($object, "+", 0) == 1) {
                $obj_exp = explode("+", $object);
                $object = ($obj_exp[0]) / 1000;
            }

            $return_date_time = new DateTime(date('Y-m-d\TH:i:s', $object), null);

            return $return_date_time->format('Y-m-d\TH:i:s');
//            return $object;
        } elseif (in_array($class, array('string', 'int', 'float', 'bool'))) {
            settype($object, $class);
            return $object;
        } else {
            $instance = new $class(); // this instantiates class named $class
            $classVars = get_class_vars($class);
        }

        foreach ($object as $property => $value) {

            // Need to handle possible pluralization differences
            $true_property = $property;

            if (!property_exists($class, $true_property)) {
                if (substr($property, -1) == 's') {
                    $true_property = substr($property, 0, -1);
                }
            }

            if (array_key_exists($true_property, $classVars['swaggerTypes'])) {
                $type = $classVars['swaggerTypes'][$true_property];
            } else {
                $type = 'string';
            }

            if (in_array($type, array('string', 'int', 'float', 'bool'))) {
                settype($value, $type);
                $instance->{$true_property} = $value;
            } elseif (preg_match("/array<(.*)>/", $type, $matches)) {
                $sub_class = $matches[1];
                $instance->{$true_property} = array();
                foreach ($value as $sub_property => $sub_value) {
                    $instance->{$true_property}[] = self::deserialize($sub_value,
                        $sub_class);
                }
            } else {
                $instance->{$true_property} = self::deserialize($value, $type);
            }
        }
        return $instance;
    }
}