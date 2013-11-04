<?php
App::uses('APIClient', 'OpiaProxy.Model');

class TravelApi
{
    /**
     * @var $apiClient APIClient
     */
    private $apiClient;

    function __construct()
    {
        $this->apiClient = new APIClient();
        //Don't cache anything from this api, Translink states we should not.
        //Also Travel plans are quite specific to each customer.
        //the 'at' param also makes every request unique
        $this->apiClient->no_cache = true;
    }

    /**
     * plan
     * Generates travel plans between two locations
     * @param $fromLocationId string  Location.Id to plan a journey from (optional)
     * @param $toLocationId string  Location.Id to plan a journey to (optional)
     * @param $timeMode string  Whether to arrive before, after, etc the time specified in At parameter. LeaveAfter = 0, ArriveBefore = 1, FirstServices = 2, LastServices = 3 (optional)
     * @param $at DateTime Date and time to base the journey around (optional)
     * @param $vehicleTypes string  Types of vehicles to travel on. Defaults to all vehicles if NULL. None = 0, Bus = 2, Ferry = 4, Train = 8, Walk = 16 (optional)
     * @param $walkSpeed string  Speed to assume customer can walk between journey legs, and to/from the From and To locations. Slow = 0, Normal = 1, Fast = 2 (optional)
     * @param $maximumWalkingDistanceM int   Max distance in meters the client can walk from &lt;b&gt;fromLocationId&lt;/b&gt; to reach a vehicle, or from drop off location to &lt;b&gt;toLocationId&lt;/b&gt; (optional)
     * @param $serviceTypes string  Types of services to consider, eg Regular, Express, etc. Defaults to any if not NULL. Regular = 1, Express = 2, NightLink = 4, School = 8, Industrial = 16 (optional)
     * @param $fareTypes string  Types of fares to consider, eg Prepaid, Free, etc. Defaults to any if not NULL. Free = 1, Standard = 2, Prepaid = 4 (optional)
     * @return GetTravelOptionsResult
     */
    public function plan($fromLocationId = null, $toLocationId = null, $timeMode = null, $at = null, $vehicleTypes = null, $walkSpeed = null, $maximumWalkingDistanceM = null, $serviceTypes = null, $fareTypes = null)
    {
        //parse inputs
        $resourcePath = "/travel/rest/plan/{fromLocationId}/{toLocationId}";
        $resourcePath = str_replace("{format}", "json", $resourcePath);
        $method = "GET";
        $queryParams = array();
        $headerParams = array();

        //Default values subbed in so you can just provide a from and to location like you can on the Translink website
        //These options were also stated as optional by teh Swagger test site, but are in fact required.
        if ($timeMode != null) {
            $queryParams['timeMode'] = $this->apiClient->toQueryValue($timeMode);
        } else {
            $queryParams['timeMode'] = 0;
        }
        if ($at != null) {
            $queryParams['at'] = $this->apiClient->toQueryValue($at);
        } else {
            $queryParams['at'] = date('Y/m/d H:i:s');
        }
        if ($vehicleTypes != null) {
            $queryParams['vehicleTypes'] = $this->apiClient->toQueryValue($vehicleTypes);
        }
        if ($walkSpeed != null) {
            $queryParams['walkSpeed'] = $this->apiClient->toQueryValue($walkSpeed);
        } else {
            $queryParams['walkSpeed'] = 1;
        }
        if ($maximumWalkingDistanceM != null) {
            $queryParams['maximumWalkingDistanceM'] = $this->apiClient->toQueryValue($maximumWalkingDistanceM);
        } else {
            $queryParams['maximumWalkingDistanceM'] = 101;
        }
        //
        if ($serviceTypes != null) {
            $queryParams['serviceTypes'] = $this->apiClient->toQueryValue($serviceTypes);
        }
        if ($fareTypes != null) {
            $queryParams['fareTypes'] = $this->apiClient->toQueryValue($fareTypes);
        }
        if ($fromLocationId != null) {
            $resourcePath = str_replace("{" . "fromLocationId" . "}",
                $this->apiClient->toPathValue($fromLocationId), $resourcePath);
        }
        if ($toLocationId != null) {
            $resourcePath = str_replace("{" . "toLocationId" . "}",
                $this->apiClient->toPathValue($toLocationId), $resourcePath);
        }
        //make the API Call
        if (!isset($body)) {
            $body = null;
        }
        $response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, $body, $headerParams);


        if (!$response) {
            return null;
        }

        $responseObject = $this->apiClient->deserialize($response, 'GetTravelOptionsResult');
        return $responseObject;
    }

    /**
     * plan_url
     * Generates a URL to Translink's Journey Planner which suggests possible journeys
     * @param $fromLocationId string  Location to plan a journey from. See remarks section for allowed formats (optional)
     * @param $toLocationId string  Location to plan a journey to. See remarks section for allowed formats (optional)
     * @param $timeMode string  Whether to arrive before, after, etc the time specified in At parameter. LeaveAfter = 0, ArriveBefore = 1, FirstServices = 2, LastServices = 3 (optional)
     * @param $at DateTime Date and time to base the journey around (optional)
     * @param $vehicleTypes string  Types of vehicles to travel on. Defaults to all vehicles if NULL. None = 0, Bus = 2, Ferry = 4, Train = 8, Walk = 16 (optional)
     * @param $walkSpeed string  Speed to assume customer can walk between journey legs, and to/from the From and To locations. Slow = 0, Normal = 1, Fast = 2 (optional)
     * @param $maximumWalkingDistanceM int Maximum distance in meters the client would consider walking from &lt;b&gt;fromLocationId&lt;/b&gt; to reach a vehicle, or from the drop-off location to their destination &lt;b&gt;toLocationId&lt;/b&gt; (optional)
     * @param $serviceTypes string  Types of services to consider, eg Regular, Express, etc. Defaults to any if not NULL. Regular = 1, Express = 2, NightLink = 4, School = 8, Industrial = 16 (optional)
     * @param $fareTypes string  Types of fares to consider, eg Prepaid, Free, etc. Defaults to any if not NULL. Free = 1, Standard = 2, Prepaid = 4 (optional)
     * @return GetTravelOptionsUrlResult
     */
    public function plan_url($fromLocationId = null, $toLocationId = null, $timeMode = null, $at = null, $vehicleTypes = null, $walkSpeed = null, $maximumWalkingDistanceM = null, $serviceTypes = null, $fareTypes = null)
    {
        //parse inputs
        $resourcePath = "/travel/rest/plan-url/{fromLocationId}/{toLocationId}";
        $resourcePath = str_replace("{format}", "json", $resourcePath);
        $method = "GET";
        $queryParams = array();
        $headerParams = array();

        //Default values subbed in so you can just provide a from and to location like you can on the Translink website
        //These options were also stated as optional by teh Swagger test site, but are in fact required.
        if ($timeMode != null) {
            $queryParams['timeMode'] = $this->apiClient->toQueryValue($timeMode);
        } else {
            $queryParams['timeMode'] = 0;
        }
        if ($at != null) {
            $queryParams['at'] = $this->apiClient->toQueryValue($at);
        } else {
            $queryParams['at'] = date('Y/m/d H:i:s');
        }
        if ($vehicleTypes != null) {
            $queryParams['vehicleTypes'] = $this->apiClient->toQueryValue($vehicleTypes);
        }
        if ($walkSpeed != null) {
            $queryParams['walkSpeed'] = $this->apiClient->toQueryValue($walkSpeed);
        } else {
            $queryParams['walkSpeed'] = 1;
        }
        if ($maximumWalkingDistanceM != null) {
            $queryParams['maximumWalkingDistanceM'] = $this->apiClient->toQueryValue($maximumWalkingDistanceM);
        } else {
            $queryParams['maximumWalkingDistanceM'] = 1000;
        }
        //
        if ($serviceTypes != null) {
            $queryParams['serviceTypes'] = $this->apiClient->toQueryValue($serviceTypes);
        }
        if ($fareTypes != null) {
            $queryParams['fareTypes'] = $this->apiClient->toQueryValue($fareTypes);
        }
        if ($fromLocationId != null) {
            $resourcePath = str_replace("{" . "fromLocationId" . "}",
                $this->apiClient->toPathValue($fromLocationId), $resourcePath);
        }
        if ($toLocationId != null) {
            $resourcePath = str_replace("{" . "toLocationId" . "}",
                $this->apiClient->toPathValue($toLocationId), $resourcePath);
        }
        //make the API Call
        if (!isset($body)) {
            $body = null;
        }
        $response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, $body, $headerParams);

        if (!$response) {
            return null;
        }

        $responseObject = $this->apiClient->deserialize($response, 'GetTravelOptionsUrlResult');
        return $responseObject;
    }
}