<?php
declare(strict_types=1);


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AutocompleteControllerTest extends WebTestCase
{
    public function testNonAmbiguousAutocomplete()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/autocomplete/',
            [],
            [],
            [],
            '{"input":"timelin"}'
        );

        $result = json_decode($client->getResponse()->getContent());

        $this->assertEquals('e', $result->autocomplete);
        $this->assertFalse(isset($result->suggestions));
    }

    public function testAmbiguousAutocomplete()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/autocomplete/',
            [],
            [],
            [],
            '{"input":"c"}'
        );

        $result = json_decode($client->getResponse()->getContent());

        $this->assertTrue(count($result->suggestions) > 1);
        $this->assertFalse(isset($result->autocomplete));
    }

    public function testNoSuggestions()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/autocomplete/',
            [],
            [],
            [],
            '{"input":"somecommandthatdoesnotexist"}'
        );

        $result = json_decode($client->getResponse()->getContent());

        $this->assertFalse(isset($result->autocomplete));
        $this->assertFalse(isset($result->suggestions));
    }
}
