<?php
App::uses('NetworkApi', 'OpiaProxy.Model');

/**
 * Agency Test Case
 *
 */
class LocationApiTest extends CakeTestCase
{
    /**
     * @var NetworkApi
     */
    public $NetworkApi;

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
        $this->NetworkApi = new NetworkApi();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->NetworkApi);
        parent::tearDown();
    }

    //TODO tests....
    public function test_should_get_stop_timetables(){
        $expected_result ='{"Code":"CAIP","Name":"Caboolture via Brisbane City to Ipswich","Direction":"8","Vehicle":"8","ServiceType":"1","IsPrepaid":false,"IsExpress":false,"IsFree":false,"IsTransLinkService":true}';

        //Date should be a weekday(timetable is likely to be different on the weekend, must be in the Y/m/d format
        $response =  $this->NetworkApi->stop_timetables('600287','2013/10/22');

        $this->assertEqual($response->StopTimetables[0]->Routes[0], $expected_result);
    }

}
