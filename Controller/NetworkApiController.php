<?php
App::uses('NetworkApi', 'OpiaProxy.Model');

class NetworkApiController extends OpiaProxyAppController
{
    /**
     * @var $location_api_client NetworkApi
     */
    private $network_api_client;

    public function beforeFilter()
    {
        $this->network_api_client = new NetworkApi();

        $this->viewPath = 'api_response';
        $this->view = "base_api_response";
    }

    /**
     * stop-timetables
     * Retrieves Stop Timetables for a particular date
     * @return GetStopTimetablesResult
     */
    public function stop_timetables()
    {
        $stopIds = $date = $routeFilter = null;
        extract($this->request->query);

        $obj = $this->network_api_client->stop_timetables($stopIds, $date, $routeFilter);

        $this->set('response', $obj);
    }

    /**
     * route-timetables
     * Retrieves timetables for one or more Routes on a date.
     * @return GetRouteTimetablesResult
     */
    public function route_timetables()
    {
        $routeCodes= $vehicleType= $date= $filterToStartEndStops= $directions = null;
        extract($this->request->query);

        $obj = $this->network_api_client->route_timetables($routeCodes, $vehicleType, $date, $filterToStartEndStops, $directions);

        $this->set('response', $obj);
    }

    /**
     * trips
     * Retrieves details of a specific trip, including all its stops
     * @return GetTripsResult
     */
    public function trips()
    {
        $ids = null;
        extract($this->request->query);

        $obj = $this->network_api_client->trips($ids);

        $this->set('response', $obj);
    }

    /**
     * trip_map_path
     * Retrieves geo-coordinates a trip takes in polyline format
     * @return GetTripMapPointsResult
     */
    public function trip_map_path()
    {
        $tripId = null;
        extract($this->request->query);

        $obj = $this->network_api_client->trip_map_path($tripId);

        $this->set('response', $obj);
    }

    /**
     * route_map_path
     * Retrieves geo-coordinates a route takes
     * @return GetRouteMapPointsResult
     */
    public function route_map_path()
    {
        $routeCode= $vehicleType= $date = null;
        extract($this->request->query);

        $obj = $this->network_api_client->route_map_path($routeCode, $vehicleType, $date);

        $this->set('response', $obj);
    }

    /**
     * routes
     * Retrieves Routes operating on a particular date
     * @return GetRoutesResult
     */
    public function routes()
    {
        $date=$vehicleTypes =  $routeCodes  = null;
        extract($this->request->query);

        $obj = $this->network_api_client->routes($date, $vehicleTypes, $routeCodes);

        $this->set('response', $obj);
    }

    /**
     * route_lines
     * Retrieves all Routes travelling on a particular day by Line
     * @return GetRouteLinesResult
     */
    public function route_lines()
    {
        $vehicleType= $date = null;
        extract($this->request->query);

        $obj = $this->network_api_client->route_lines($vehicleType, $date);

        $this->set('response', $obj);
    }
}