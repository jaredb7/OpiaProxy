<?php
App::uses('APIClient', 'OpiaProxy.Model');

/**
 *
 * NOTE: This class is auto generated by the swagger code generator program. Do not edit the class manually.
 */
class VersionApi
{

    /**
     * @var $apiClient APIClient
     */
    private $apiClient;

    function __construct()
    {
        $this->apiClient = new APIClient();
    }

    /**
     * api
     * API's public interface version
     * @return string
     */
    public function api()
    {
        //parse inputs
        $resourcePath = "/version/rest/api";
        $resourcePath = str_replace("{format}", "json", $resourcePath);
        $method = "GET";
        $queryParams = array();
        $headerParams = array();

        //make the API Call
        if (!isset($body)) {
            $body = null;
        }
        $response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, $body, $headerParams);

        if (!$response) {
            return null;
        }

        $responseObject = $this->apiClient->deserialize($response, 'string');
        return $responseObject;
    }

    /**
     * build
     * Internal Translink build number of the API
     * @return string
     */
    public function build()
    {
        //parse inputs
        $resourcePath = "/version/rest/build";
        $resourcePath = str_replace("{format}", "json", $resourcePath);
        $method = "GET";
        $queryParams = array();
        $headerParams = array();

        //make the API Call
        if (!isset($body)) {
            $body = null;
        }
        $response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, $body, $headerParams);

        if (!$response) {
            return null;
        }

        $responseObject = $this->apiClient->deserialize($response, 'string');
        return $responseObject;
    }
}