<?php

namespace App\Tests\Controller;

use App\Tests\ComPassManWebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccessEntryControllerTest extends WebTestCase
{
    public function testIndex1()
    {
        $client = static::createClient();

        $client->request('GET', '/access_entry/', [], [], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => 'test',
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}