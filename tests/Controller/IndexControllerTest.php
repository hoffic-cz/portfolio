<?php
declare(strict_types=1);


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testPageSuccessCode()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPageCorrectContent()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertContains('Hi, my name is', $client->getResponse()->getContent());
    }
}
