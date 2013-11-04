<?php
define('CAKE_OPIA_VER','0.0.5');
/**
 * OPIA API ACCESS CREDENTIALS
 */
Configure::write('OpiaProxy.opiaLogin', "");
Configure::write('OpiaProxy.opiaPassword', "");
Configure::write('OpiaProxy.api_key', "special_key"); //Maybe for some future OPIA implementation

/**
 * Proxy storage type, either :: file|mysql|sqlite
 */
Configure::write('OpiaProxy.perist_type', "sqlite");

/**
 * CACHE TTL'S (Specified values are in seconds)
 */
/** Default cache time is 24hrs */
Configure::write('OpiaProxy.cache_ttl.data', 86400);
/** DATE SCOPED SPECIAL */
Configure::write('OpiaProxy.cache_ttl.Timetable', 604800);
Configure::write('OpiaProxy.cache_ttl.Trip', 604800);
Configure::write('OpiaProxy.cache_ttl.Route', 604800);
/** LOCATION INFO */
Configure::write('OpiaProxy.cache_ttl.Stop', 604800);
Configure::write('OpiaProxy.cache_ttl.Location', 86400);
Configure::write('OpiaProxy.cache_ttl.Address', 604800);
/** */
Configure::write('OpiaProxy.cache_ttl.Line', 604800);
Configure::write('OpiaProxy.cache_ttl.RoutesByLine', 604800);
Configure::write('OpiaProxy.cache_ttl.StopTimetable', 604800);
Configure::write('OpiaProxy.cache_ttl.StopTimetableTrip', 604800);
/** Client Journey related -- should not cache these */
Configure::write('OpiaProxy.cache_ttl.Fare', 0);
Configure::write('OpiaProxy.cache_ttl.Itinerary', 0);
Configure::write('OpiaProxy.cache_ttl.Leg', 0);
Configure::write('OpiaProxy.cache_ttl.TravelOptions', 0);

/**
 * API ENDPOINTS
 */
Configure::write('OpiaProxy.api_endpoint.Network', "/network");
Configure::write('OpiaProxy.api_endpoint.Location', "/location");
Configure::write('OpiaProxy.api_endpoint.Travel', "/travel");
Configure::write('OpiaProxy.api_endpoint.Service', "/?service?"); //TBA
Configure::write('OpiaProxy.api_endpoint.Version', "/version");
Configure::write('OpiaProxy.api_endpoint.Base', "https://opia.api.translink.com.au/v1");


/**
 * Configures logging options for the OpiaProxy
 */
CakeLog::config('opia_proxy', array(
    'engine' => 'FileLog',
    'types' => array('info', 'error', 'warning'),
    'scopes' => array('opia_proxy'),
    'file' => 'opia_proxy.log',
));

/**
 * Predis Client
 */
//require(APP . 'Vendor' . DS . 'Predis' . DS . 'Autoloader.php');
//Predis\Autoloader::register();