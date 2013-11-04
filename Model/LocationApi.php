<?php
App::uses('APIClient', 'OpiaProxy.Model');

class LocationApi
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

    /**
     * resolve
     * Suggests landmarks, stops, addresses, etc from free-form text
     * @param $input string Free form text to resolve (required)
     * @param $filter string Types of locations to limit search to, or 'None' for no filtering. None = 0, Landmark = 1, StreetAddress = 2, Stop = 3, GeographicPosition = 4 (required)
     * @param $maxResults int Maximum matches to return. Hard limit of 50 (required)
     * @throws Exception on missing argument
     * @return ResolveInputResult
     */
    public function resolve($input, $filter, $maxResults)
    {
        //parse inputs
        $resourcePath = "/location/rest/resolve";
        $resourcePath = str_replace("{format}", "json", $resourcePath);
        $method = "GET";
        $queryParams = array();
        $headerParams = array();

        if ($input != null) {
            $queryParams['input'] = $this->apiClient->toQueryValue($input);
        }
        if ($filter != null) {
            $queryParams['filter'] = $this->apiClient->toQueryValue($filter);
        }else{
            $queryParams['filter'] = 0;
        }
        if ($maxResults != null) {
            $queryParams['maxResults'] = $this->apiClient->toQueryValue($maxResults);
        }
        //make the API Call
        if (!isset($body)) {
            $body = null;
        }
        $response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, $body, $headerParams);


        if (!$response) {
            return null;
        }

        $responseObject = $this->apiClient->deserialize($response, 'ResolveInputResult');

        return $responseObject;
    }

    /**
     * stops-at-landmark
     * Retrieves a list of Stops located at a Landmark
     * @param $locationId string  Location.Id of a Landmark (required)
     * @return GetLandmarkStopsResult
     */
    public function stops_at_landmark($locationId)
    {
        //parse inputs
        $resourcePath = "/location/rest/stops-at-landmark/{locationId}";
        $resourcePath = str_replace("{format}", "json", $resourcePath);
        $method = "GET";
        $queryParams = array();
        $headerParams = array();

        if ($locationId != null) {
            $resourcePath = str_replace("{" . "locationId" . "}",
                $this->apiClient->toPathValue($locationId), $resourcePath);
        }

        //make the API Call
        if (!isset($body)) {
            $body = null;
        }
        $response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, $body, $headerParams);


        if (!$response) {
            return null;
        }

        $responseObject = $this->apiClient->deserialize($response, 'GetLandmarkStopsResult');

        return $responseObject;
    }

    /**
     * stops-nearby
     * Locates stops close to a specific location
     * @param $locationId string  The location to search for stops nearby to (required)
     * @param $radiusM int  Maximum radius, in metres, to search around. Max of 3000m. Recommended: 1000 (required)
     * @param $useWalkingDistance bool  Whether the radius applies to the actual walking distance or 'as the crow flies'. Default: false (required)
     * @param $maxResults int  Max results to return. Limit of 50 (required)
     * @return GetNearbyStopsResult
     */
    public function stops_nearby($locationId, $radiusM, $useWalkingDistance, $maxResults)
    {
        //parse inputs
        $resourcePath = "/location/rest/stops-nearby/{locationId}";
        $resourcePath = str_replace("{format}", "json", $resourcePath);
        $method = "GET";
        $queryParams = array();
        $headerParams = array();

        if ($radiusM != null) {
            $queryParams['radiusM'] = $this->apiClient->toQueryValue($radiusM);
        }
        if ($useWalkingDistance != null) {
            $queryParams['useWalkingDistance'] = $this->apiClient->toQueryValue($useWalkingDistance);
        }
        if ($maxResults != null) {
            $queryParams['maxResults'] = $this->apiClient->toQueryValue($maxResults);
        }
        if ($locationId != null) {
            $resourcePath = str_replace("{" . "locationId" . "}",
                $this->apiClient->toPathValue($locationId), $resourcePath);
        }
        //make the API Call
        if (!isset($body)) {
            $body = null;
        }
        $response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, $body, $headerParams);


        if (!$response) {
            return null;
        }

        $responseObject = $this->apiClient->deserialize($response, 'GetNearbyStopsResult');

        return $responseObject;
    }

    /**
     * stops
     * Retrieves a list of stops by their Stop ID
     * @param $ids string  List of Stops ID's to retrieve, eg 000026. Max 100 per request (required)
     * @return GetStopsResult
     */
    public function stops($ids)
    {
        //parse inputs
        $resourcePath = "/location/rest/stops";
        $resourcePath = str_replace("{format}", "json", $resourcePath);
        $method = "GET";
        $queryParams = array();
        $headerParams = array();

        if ($ids != null) {
            $queryParams['ids'] = $this->apiClient->toQueryValue($ids);
        }
        //make the API Call
        if (!isset($body)) {
            $body = null;
        }
        $response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, $body, $headerParams);

        if (!$response) {
            return null;
        }

        $responseObject = $this->apiClient->deserialize($response, 'GetStopsResult');

        return $responseObject;
    }

    /**
     * locations
     * Retrieves one or more locations by their ID
     * @param $ids string  List of Location.Id's to retrieve. Max 20 per call (required)
     * @return GetLocationsResult
     */
    public function locations($ids)
    {
        //parse inputs
        $resourcePath = "/location/rest/locations";
        $resourcePath = str_replace("{format}", "json", $resourcePath);
        $method = "GET";
        $queryParams = array();
        $headerParams = array();

        if ($ids != null) {
            $queryParams['ids'] = $this->apiClient->toQueryValue($ids);
        }
        //make the API Call
        if (!isset($body)) {
            $body = null;
        }
        $response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, $body, $headerParams);

        if (!$response) {
            return null;
        }

        $responseObject = $this->apiClient->deserialize($response, 'GetLocationsResult');

        return $responseObject;
    }
}