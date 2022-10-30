<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TagsTest extends WebTestCase
{
    private Generator $faker;
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->faker = Factory::create();
        $this->client = static::createClient();

        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'test@example']);
        $this->client->loginUser($user);
    }

    public function testSearch(): void
    {
        // $client = static::createClient();
        $this->client->request(Request::METHOD_GET, '/api/tags');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $this->client->request(Request::METHOD_GET, '/api/tags?query=task');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testAdd(): void
    {
        // $faker = Factory::create();
        // $client = static::createClient();
        $this->client->request(Request::METHOD_POST, '/api/tags', [], [], [], json_encode([
            'name' => $this->faker->word(),
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $tag = json_decode($this->client->getInternalResponse()->getContent(), true);
        $this->assertNotEmpty($tag['id']);
    }
}
