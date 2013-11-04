<?php
App::uses('AppController', 'Controller');

class OpiaProxyAppController extends AppController
{

    //TODO: Proxy model & data storage (SQLite, MySQL, Redis)
    //TODO: proxy interface
    //TODO: APICLient should invoke Proxy so you don't have to worry about going through the proxy
    //TODO: Cache clearing hourly, either through CRON or a check done every request to see if it's been 1hr sinc the last clean


    public function beforeRender()
    {
        $this->viewClass = 'Json';
    }
}