<?php
App::uses('APIClient', 'OpiaProxy.Model');

class GtfsrApi
{

    /**
     * @var $apiClient APIClient
     */
    private $apiClient;

    function __construct()
    {
        //TODO shortcut methods, eg. get next arrivals at a station (grouping individual platforms within)

        $this->apiClient = new APIClient();
    }

    //TODO api to the GTFSR feed

}