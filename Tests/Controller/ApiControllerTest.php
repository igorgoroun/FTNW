<?php

namespace IgorGoroun\FTNWBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testUplinknew()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/uplink/new');
    }

}
