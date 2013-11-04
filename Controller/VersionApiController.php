<?php
App::uses('VersionApi', 'OpiaProxy.Model');

class VersionApiController extends OpiaProxyAppController
{

    /**
     * @var $location_api_client VersionApi
     */
    private $version_api_client;

    public function beforeFilter()
    {
        $this->version_api_client = new VersionApi();

        $this->viewPath = 'api_response';
        $this->view = "base_api_response";
    }

    /**
     * api
     * API's public interface version
     * @return string
     */
    public function api()
    {
        $obj = $this->version_api_client->api();

        $this->set('response', $obj);
    }

    /**
     * build
     * Internal Translink build number of the API
     * @return string
     */
    public function build()
    {
        $obj = $this->version_api_client->build();

        $this->set('response', $obj);
    }
}