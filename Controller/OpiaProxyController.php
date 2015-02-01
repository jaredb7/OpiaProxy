<?php
App::uses('AppController', 'Controller');
App::uses('APIClient', 'OpiaProxy.Model');
App::uses('HelpfulUtils', 'Lib');

class OpiaProxyController extends OpiaProxyAppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();

//        $this->Auth->allow('');

        if ($this->Auth->user('role') == 'admin') {
            $this->Auth->allow('admin_index', 'admin_test');
        }

    }

    public function beforeRender()
    {
        $this->viewClass = 'View';
    }


    /**
     * Admin index for monitoring workers
     */
    public function admin_index()
    {
        $api_client = new APIClient();
        $cache_items = $api_client->_proxy_client->list_cache();

        //Build info about the cache
        $cache_status = array(
            'cache_type' => Configure::read('OpiaProxy.perist_type'),
            'cache_items' => $cache_items);


        //Now check APIs - this is crude but it works
        //Switch of caching
        Configure::write('OpiaProxy.cache.no_cache', true);
        //init everything to false
        $api_status = array('network' => false, 'location' => false, 'travel' => false, 'version' => false);

        //Network
        $net_controller = new NetworkApi();
        $net_result = $net_controller->stop_timetables('600428', date('Y-m-d'));
//        usleep(1000);
        //Location
        $loc_controller = new LocationApi();
        $loc_result = $loc_controller->resolve('toowong', 0, 2);
//        usleep(1000);

        //Travel
        $travel_controller = new TravelApi();
        $tavel_result = $travel_controller->plan('LM:Toowong station (Train Stations)', 'LM:Domestic Airport station (Train Stations)', 0, date('Y-m-d G:i:s'));
//        usleep(1000);

        //Version
        $version_controller = new VersionApi();
        $ver_result = $version_controller->api();

        //Check em all
        if (!empty($net_result) || $net_result != null) {
            $api_status['network'] = true;
        }
        if (!empty($loc_result) || $loc_result != null) {
            $api_status['location'] = true;
        }
        if (!empty($tavel_result) || $tavel_result != null) {
            $api_status['travel'] = true;
        }
        if (!empty($ver_result) || $ver_result != null) {
            $api_status['version'] = true;
        }

        $log = HelpfulUtils::tail(LOGS . "opia_proxy.log", 400);
        //Turn the log into an array so we can iterate over the lines
        $log_arr = array_reverse(explode("\n", $log));

        $this->set('api_status', $api_status);
        $this->set('cache_status', $cache_status);
        $this->set('log_tail', $log_arr);
    }

    public function admin_purge()
    {
        $api_client = new APIClient();
        $api_client->_proxy_client->purge();

        $this->redirect(array(
            'controller' => 'opia_proxy',
            'action' => 'index',
            'full_base' => true
        ));
    }
}