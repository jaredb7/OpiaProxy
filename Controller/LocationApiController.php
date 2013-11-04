<?php
App::uses('LocationApi', 'OpiaProxy.Model');

class LocationApiController extends OpiaProxyAppController
{
    /**
     * @var $location_api_client LocationApi
     */
    private $location_api_client;

    public function beforeFilter()
    {
        $this->location_api_client = new LocationApi();

        $this->viewPath = 'api_response';
        $this->view = "base_api_response";
    }

    /**
     * resolve
     * Suggests landmarks, stops, addresses, etc from free-form text
     * @return ResolveInputResult
     */
    public function resolve()
    {
        $input = $filter = $maxResults = null;
        extract($this->request->query);

        //resolve input
        $obj = $this->location_api_client->resolve($input, $filter, $maxResults);

        $this->set('response', $obj);
    }

    /**
     * stops-at-landmark
     * Retrieves a list of Stops located at a Landmark
     * @return GetLandmarkStopsResult
     */
    public function stops_at_landmark()
    {
        $locationid = null;
        $locationid = $this->passedArgs[0];

        if($locationid == null){
            extract($this->request->query);
        }

        $obj = $this->location_api_client->stops_at_landmark($locationid);

        $this->set('response', $obj);
    }

    /**
     * stops-nearby
     * Locates stops close to a specific location
     * @return GetNearbyStopsResult
     */
    public function stops_nearby()
    {
        $locationid = $radiusM = $useWalkingDistance = $maxResults = null;
        $locationid = $this->passedArgs[0];
        extract($this->request->query);

        $obj = $this->location_api_client->stops_nearby($locationid, $radiusM, $useWalkingDistance, $maxResults);

        $this->set('response', $obj);
    }

    /**
     * stops
     * Retrieves a list of stops by their Stop ID
     * @return GetStopsResult
     */
    public function stops()
    {
        $ids = null;
        extract($this->request->query);

        $obj = $this->location_api_client->stops($ids);

        $this->set('response', $obj);
    }

    /**
     * locations
     * Retrieves one or more locations by their ID
     * @return GetLocationsResult
     */
    public function locations()
    {
        $ids = null;
        extract($this->request->query);

        $obj = $this->location_api_client->locations($ids);

        $this->set('response', $obj);
    }
}