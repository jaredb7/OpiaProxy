<?php
App::uses('AppController', 'Controller');

class OpiaProxyAppController extends AppController
{
    public function beforeRender()
    {
        $this->viewClass = 'Json';
    }
}