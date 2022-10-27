<?php

namespace App\Tests\Functional;

use Faker\Factory;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TagsTest extends WebTestCase
{
    public function testSearch(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/api/tags');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $client->request(Request::METHOD_GET, '/api/tags?query=task');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testAdd(): void
    {
        $faker = Factory::create();
        $client = static::createClient();
        $client->request(Request::METHOD_POST, '/api/tags', [], [], [], json_encode([
            'name' => $faker->word(),
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $tag = json_decode($client->getInternalResponse()->getContent(), true);
        $this->assertNotEmpty($tag['id']);
    }
}
