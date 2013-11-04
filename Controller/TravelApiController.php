<?php
App::uses('TravelApi', 'OpiaProxy.Model');

class TravelApiController extends OpiaProxyAppController
{

    /**
     * @var $location_api_client TravelApi
     */
    private $travel_api_client;

    public function beforeFilter()
    {
        $this->travel_api_client = new TravelApi();

        $this->viewPath = 'api_response';
        $this->view = "base_api_response";
    }

    /**
     * plan
     * Generates travel plans between two locations
     * @return GetTravelOptionsResult
     */
    public function plan()
    {
        $fromLocationId = $toLocationId = $timeMode = $at = $vehicleTypes = $walkSpeed = $maximumWalkingDistanceM = $serviceTypes = $fareTypes = null;
        extract($this->request->query);

        $obj = $this->travel_api_client->plan($fromLocationId, $toLocationId, $timeMode, $at, $vehicleTypes, $walkSpeed, $maximumWalkingDistanceM, $serviceTypes, $fareTypes);

        $this->set('response', $obj);
    }

    /**
     * plan_url
     * Generates a URL to Translink's Journey Planner which suggests possible journeys
     * @return GetTravelOptionsUrlResult
     */
    public function plan_url()
    {
        $fromLocationId = $toLocationId = $timeMode = $at = $vehicleTypes = $walkSpeed = $maximumWalkingDistanceM = $serviceTypes = $fareTypes = null;
        extract($this->request->query);

        $obj = $this->travel_api_client->plan_url($fromLocationId, $toLocationId, $timeMode, $at, $vehicleTypes, $walkSpeed, $maximumWalkingDistanceM, $serviceTypes, $fareTypes);

        $this->set('response', $obj);
    }
}