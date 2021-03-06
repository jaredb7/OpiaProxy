<?php
App::uses('APIClient', 'OpiaProxy.Model');

/**
 *
 * NOTE: This class is auto generated by the swagger code generator program. Do not edit the class manually.
 */
class NetworkApi
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
     * stop-timetables
     * Retrieves Stop Timetables for a particular date
     * @param $stopIds string List of Stop.StopIds or &lt;b&gt;Stop.Id&lt;/b&gt;s to retrieve timetables for. Max 50 per request (required)
     * @param $date DateTime  Date to retrieve timetables for. Time component is ignored (required)
     * @param $routeFilter string Optional list of routes travelling through &lt;b&gt;stopIds&lt;/b&gt; to filter results to (optional)
     * @return GetStopTimetablesResult
     */
    public function stop_timetables($stopIds, $date, $routeFilter = null)
    {
        //parse inputs
        $resourcePath = "/network/rest/stop-timetables";
        $resourcePath = str_replace("{format}", "json", $resourcePath);
        $method = "GET";
        $queryParams = array();
        $headerParams = array();

        if ($stopIds != null) {
            $queryParams['stopIds'] = $this->apiClient->toQueryValue($stopIds);
        }
        if ($date != null) {
            $queryParams['date'] = $this->apiClient->toQueryValue($date);
        }
        if ($routeFilter != null) {
            $queryParams['routeFilter'] = $this->apiClient->toQueryValue($routeFilter);
        }
        //make the API Call
        if (!isset($body)) {
            $body = null;
        }
        $response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, $body, $headerParams);


        if (!$response) {
            return null;
        }

        $responseObject = $this->apiClient->deserialize($response, 'GetStopTimetablesResult');
        return $responseObject;

    }

    /**
     * route-timetables
     * Retrieves timetables for one or more Routes on a date.
     * @param $routeCodes string The route codes or train lines to retrieve timetables for. Hard limit of 50 Route Codes per request (required)
     * @param $vehicleType string Type of vehicle, eg Bus. None = 0, Bus = 2, Ferry = 4, Train = 8, Walk = 16 (required)
     * @param $date DateTime  Date to retrieve the timetable for. Time component is ignored (required)
     * @param $filterToStartEndStops bool  Whether to filter the resulting Timetable[n].Trips[i].StopIds list to just start/end stop (required)
     * @param $directions string Optional list of directions to filter the resulting timetables to. North = 0, South = 1, East = 2, West = 3, Inbound = 4, Outbound = 5, Inward = 6, Outward = 7, Upward = 8, Downward = 9, Clockwise = 10, Counterclockwise = 11, Direction1 = 12, Direction2 = 13, Null = 14 (optional)
     * @return GetRouteTimetablesResult
     */
    public function route_timetables($routeCodes, $vehicleType, $date, $filterToStartEndStops, $directions = null)
    {
        //parse inputs
        $resourcePath = "/network/rest/route-timetables";
        $resourcePath = str_replace("{format}", "json", $resourcePath);
        $method = "GET";
        $queryParams = array();
        $headerParams = array();

        if ($routeCodes != null) {
            $queryParams['routeCodes'] = $this->apiClient->toQueryValue($routeCodes);
        }
        if ($vehicleType != null) {
            $queryParams['vehicleType'] = $this->apiClient->toQueryValue($vehicleType);
        }
        if ($date != null) {
            $queryParams['date'] = $this->apiClient->toQueryValue($date);
        }
        if ($filterToStartEndStops != null) {
            $queryParams['filterToStartEndStops'] = $this->apiClient->toQueryValue($filterToStartEndStops);
        }
        if ($directions != null) {
            $queryParams['directions'] = $this->apiClient->toQueryValue($directions);
        }
        //make the API Call
        if (!isset($body)) {
            $body = null;
        }
        $response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, $body, $headerParams);

        if (!$response) {
            return null;
        }

        $responseObject = $this->apiClient->deserialize($response, 'GetRouteTimetablesResult');
        return $responseObject;
    }

    /**
     * trips
     * Retrieves details of a specific trip, including all its stops
     * @param $ids string List of Trip ID's identifiers to retrieve trip details for. Max 50 trips per request (required)
     * @return GetTripsResult
     */

    public function trips($ids)
    {
        //parse inputs
        $resourcePath = "/network/rest/trips";
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

        $responseObject = $this->apiClient->deserialize($response, 'GetTripsResult');
        return $responseObject;
    }

    /**
     * trip_map_path
     * Retrieves geo-coordinates a trip takes in polyline format
     * @param $tripId string Trip ID to retrieve map path for (optional)
     * @return GetTripMapPointsResult
     */
    public function trip_map_path($tripId = null)
    {
        //parse inputs
        $resourcePath = "/network/rest/trip-map-path";
        $resourcePath = str_replace("{format}", "json", $resourcePath);
        $method = "GET";
        $queryParams = array();
        $headerParams = array();

        if ($tripId != null) {
            $queryParams['tripId'] = $this->apiClient->toQueryValue($tripId);
        }
        //make the API Call
        if (!isset($body)) {
            $body = null;
        }
        $response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, $body, $headerParams);


        if (!$response) {
            return null;
        }

        $responseObject = $this->apiClient->deserialize($response, 'GetTripMapPointsResult');
        return $responseObject;

    }

    /**
     * route_map_path
     * Retrieves geo-coordinates a route takes
     * @param $routeCode string The Route to retrieve map points for (required)
     * @param $vehicleType string Type of vehicle represented by RouteCode, eg Bus. None = 0, Bus = 2, Ferry = 4, Train = 8, Walk = 16 (required)
     * @param $date DateTime  Date of trip. Time component is ignored (required)
     * @return GetRouteMapPointsResult
     */
    public function route_map_path($routeCode, $vehicleType, $date)
    {
        //parse inputs
        $resourcePath = "/network/rest/route-map-path";
        $resourcePath = str_replace("{format}", "json", $resourcePath);
        $method = "GET";
        $queryParams = array();
        $headerParams = array();

        if ($routeCode != null) {
            $queryParams['routeCode'] = $this->apiClient->toQueryValue($routeCode);
        }
        if ($vehicleType != null) {
            $queryParams['vehicleType'] = $this->apiClient->toQueryValue($vehicleType);
        }
        if ($date != null) {
            $queryParams['date'] = $this->apiClient->toQueryValue($date);
        }
        //make the API Call
        if (!isset($body)) {
            $body = null;
        }
        $response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, $body, $headerParams);

        if (!$response) {
            return null;
        }

        $responseObject = $this->apiClient->deserialize($response, 'GetRouteMapPointsResult');
        return $responseObject;

    }

    /**
     * routes
     * Retrieves Routes operating on a particular date
     * @param $date DateTime  Timetable date to search route codes for (not all routes operate on all days) (required)
     * @param $vehicleTypes string Optional list of vehicle types to filter results to. None = 0, Bus = 2, Ferry = 4, Train = 8, Walk = 16 (optional)
     * @param $routeCodes string Optional list of Route Codes to filter results to (optional)
     * @return GetRoutesResult
     */
    public function routes($date, $vehicleTypes = null, $routeCodes = null)
    {
        //parse inputs
        $resourcePath = "/network/rest/routes";
        $resourcePath = str_replace("{format}", "json", $resourcePath);
        $method = "GET";
        $queryParams = array();
        $headerParams = array();

        if ($date != null) {
            $queryParams['date'] = $this->apiClient->toQueryValue($date);
        }
        if ($vehicleTypes != null) {
            $queryParams['vehicleTypes'] = $this->apiClient->toQueryValue($vehicleTypes);
        }
        if ($routeCodes != null) {
            $queryParams['routeCodes'] = $this->apiClient->toQueryValue($routeCodes);
        }
        //make the API Call
        if (!isset($body)) {
            $body = null;
        }
        $response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, $body, $headerParams);

        if (!$response) {
            return null;
        }

        $responseObject = $this->apiClient->deserialize($response, 'GetRoutesResult');
        return $responseObject;

    }

    /**
     * route_lines
     * Retrieves all Routes travelling on a particular day by Line
     * @param $vehicleType string Type of vehicles to return results for. None = 0, Bus = 2, Ferry = 4, Train = 8, Walk = 16 (required)
     * @param $date DateTime: Date to retrieve data for. Time component is ignored (required)
     * @return GetRouteLinesResult
     */
    public function route_lines($vehicleType, $date)
    {
        //parse inputs
        $resourcePath = "/network/rest/route-lines";
        $resourcePath = str_replace("{format}", "json", $resourcePath);
        $method = "GET";
        $queryParams = array();
        $headerParams = array();

        if ($vehicleType != null) {
            $queryParams['vehicleType'] = $this->apiClient->toQueryValue($vehicleType);
        }
        if ($date != null) {
            $queryParams['date'] = $this->apiClient->toQueryValue($date);
        }
        //make the API Call
        if (!isset($body)) {
            $body = null;
        }
        $response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, $body, $headerParams);

        if (!$response) {
            return null;
        }

        $responseObject = $this->apiClient->deserialize($response, 'GetRouteLinesResult');
        return $responseObject;

    }
}