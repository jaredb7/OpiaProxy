<?php
/**
 * LOCATION API
 */
Router::connect('/:plugin/location/stops-at-landmark/**', array('controller' => 'location_api', 'action' => 'stops_at_landmark'));
Router::connect('/:plugin/location/stops-nearby/**', array('controller' => 'location_api', 'action' => 'stops_nearby'));
Router::connect('/:plugin/location/:action', array('controller' => 'location_api'));

/**
 * NETWORK API
 */
Router::connect('/:plugin/network/stop-timetables/**', array('controller' => 'network_api', 'action' => 'stop_timetables'));
Router::connect('/:plugin/network/route-timetables/**', array('controller' => 'network_api', 'action' => 'route_timetables'));
Router::connect('/:plugin/network/trip-map-path/**', array('controller' => 'network_api', 'action' => 'trip_map_path'));
Router::connect('/:plugin/network/route-map-path/**', array('controller' => 'network_api', 'action' => 'route_map_path'));
Router::connect('/:plugin/network/route-lines/**', array('controller' => 'network_api', 'action' => 'route_lines'));
Router::connect('/:plugin/network/:action', array('controller' => 'network_api'));

/**
 * TRAVEL API
 */
Router::connect('/:plugin/travel/plan/**', array('controller' => 'travel_api', 'action' => 'plan'));
Router::connect('/:plugin/travel/plan-url/**', array('controller' => 'travel_api', 'action' => 'plan_url'));
Router::connect('/:plugin/travel/:action', array('controller' => 'travel_api'));


/**
 * VERSION API
 */
Router::connect('/:plugin/version/:action', array('controller' => 'version_api'));