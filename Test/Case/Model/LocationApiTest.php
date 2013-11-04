<?php
App::uses('LocationApi', 'OpiaProxy.Model');

/**
 * Agency Test Case
 *
 */
class LocationApiTest extends CakeTestCase
{
    /**
     * @var LocationApi
     */
    public $LocationApi;

    public $fixtures = null;
    public $autoFixtures = false;
    public $dropTables = false;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->LocationApi = new LocationApi();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LocationApi);
        parent::tearDown();
    }


    public function test_should_resolve()
    {
        $expected_filter_0 = '{"Locations":[{"Id":"LM:Train Stations:Toowong station","Description":"Toowong station (Train Stations)","Type":"1","Position":{"Lat":-27.485475510562,"Lng":152.99325812502},"LandmarkType":"Train Stations"}]}';
        $expected_filter_1 = '{"Locations":[{"Id":"LM:Libraries:Toowong Library","Description":"Toowong Library (Libraries)","Type":"1","Position":{"Lat":-27.485430316874,"Lng":152.99222558879},"LandmarkType":"Libraries"}]}';
        $expected_filter_2 = '{"Locations":[{"Id":"AD:Ivy St, Toowong","Description":"Ivy St, Toowong","Type":"2","Position":{"Lat":-27.483126955414,"Lng":152.97993676781},"LandmarkType":"","__type":"Address:http:\/\/opia.api.translink.com.au\/2012\/04","StreetName":"Ivy","StreetNumber":"","StreetType":"St","Suburb":"Toowong"}]}';
        $expected_filter_3 = '{"Locations":[{"Id":"SI:010926","Description":"Toowong, Jephson St","Type":"3","Position":{"Lat":-27.485737158987,"Lng":152.99047429529},"LandmarkType":"","__type":"Stop:http:\/\/opia.api.translink.com.au\/2012\/04","HasParentLocation":"","Intersection1":"Sherwood Road","Intersection2":"Private Road","LocationDescription":"Jephson Street, Toowong between Sherwood Road and Private Road, approximately 31m from Private Road","ParentLocation":"","Route":{"Code":null,"Name":null,"Direction":null,"Vehicle":null,"ServiceType":null,"IsPrepaid":null,"IsExpress":null,"IsFree":null,"IsTransLinkService":null},"ServiceType":"1","StopId":"010926","StopNoteCode":"","Street":"Jephson Street","Suburb":"Toowong","Zone":"2"}]}';
        $expected_filter_4 = '{"Locations":[]}';

        $response = json_encode($this->LocationApi->resolve("toowong", 0, 1));
        $this->assertEqual($response, $expected_filter_0);

        $response = json_encode($this->LocationApi->resolve("toowong", 1, 1));
        $this->assertEqual($response, $expected_filter_1);

        $response = json_encode($this->LocationApi->resolve("toowong", 2, 1));
        $this->assertEqual($response, $expected_filter_2);

        $response = json_encode($this->LocationApi->resolve("toowong", 3, 1));
        $this->assertEqual($response, $expected_filter_3);

        $response = json_encode($this->LocationApi->resolve("toowong", 4, 1));
        $this->assertEqual($response, $expected_filter_4);
    }

    public function test_stops_at_landmark()
    {
        $expected_result = '{"StopIds":["600287","600288","600289","600290"]}';
        $response = json_encode($this->LocationApi->stops_at_landmark("LM:Train Stations:Toowong station"));

        $this->assertEqual($response, $expected_result);
    }

    public function test_stops_nearby()
    {
        $expected_result = '{"NearbyStops":[{"StopId":"600290","Distance":{"DistanceM":82,"IsApproximate":false}}]}';
        $response = json_encode($this->LocationApi->stops_nearby("LM:Train Stations:Toowong station",1000,false,1));

        $this->assertEqual($response, $expected_result);
    }

    public function test_stops()
    {
        $expected_result = '{"Stops":[{"StopId":"600287","ServiceType":"2","Zone":"1\/2","ParentLocation":{"Id":"LM:Train stations:Toowong station","Description":"Toowong station (Train stations)","Type":"1","Position":{"Lat":-27.485475510562,"Lng":152.99325812502},"LandmarkType":"Train stations"},"HasParentLocation":true,"Street":"Toowong Railway Station Track","Suburb":"Toowong","Intersection1":"Rail Track","Intersection2":"Rail Track","StopNoteCodes":"","LocationDescription":"Toowong","Routes":[],"Id":"SI:600287","Description":"Toowong station, platform 1","Type":"3","Position":{"Lat":-27.485349117024,"Lng":152.99321764102},"LandmarkType":""}]}';
        $response = json_encode($this->LocationApi->stops('600287'));

        $this->assertEqual($response, $expected_result);
    }

    public function test_locations()
    {
        $expected_result = '{"Locations":[{"Id":"LM:Train Stations:Toowong station","Description":"Toowong station (Train Stations)","Type":"1","Position":{"Lat":-27.485475510562,"Lng":152.99325812502},"LandmarkType":"Train Stations"}]}';
        $response = json_encode($this->LocationApi->locations('LM:Train Stations:Toowong station'));

        $this->assertEqual($response, $expected_result);
    }
}
