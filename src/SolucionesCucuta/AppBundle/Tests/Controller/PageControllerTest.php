<?php

namespace SolucionesCucuta\AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testCliente()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/cliente');
    }

}
