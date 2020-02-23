<?php
declare(strict_types=1);


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommandControllerTest extends WebTestCase
{
    public function testResponseFormat()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/command/',
            [],
            [],
            [],
            '{"command":"intro"}'
        );

        $this->assertTrue(is_string(json_decode($client->getResponse()->getContent())->stdout));
    }

    public function testGarbledInput()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/command/',
            [],
            [],
            [],
            '}Hello Clem!{'
        );

        self::assertEquals(500, $client->getResponse()->getStatusCode());
    }
}
