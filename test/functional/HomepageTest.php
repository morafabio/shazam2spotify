<?php

use Silex\WebTestCase;

class Homepage extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../public/service/app.php';
        //$app['debug'] = true;
        //$app['exception_handler']->disable();

        return $app;
    }

    public function testInitialPage()
    {
        $client = $this->createClient();
        $client->request('POST', '/locator/');

        $this->assertTrue($client->getResponse()->isOk());
    }
}