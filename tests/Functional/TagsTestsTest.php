<?php

namespace App\Tests\Functional;

use Faker\Factory;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class TagsTestsTest extends WebTestCase
{
    public function testAll(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/api/tags');
        $this->assertResponseIsSuccessful();

        $client->request(Request::METHOD_GET, '/api/tags?query=task');
        $this->assertResponseIsSuccessful();
    }

    public function testAdd(): void
    {
        $faker = Factory::create();
        $client = static::createClient();
        $client->request(Request::METHOD_POST, '/api/tag', [], [], [], json_encode([
            'name' => $faker->word(),
        ]));

        $this->assertResponseIsSuccessful();
    }
}
