<?php

namespace App\Tests\Functional;

use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ToDosTest extends WebTestCase
{
    public function testSearch(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/api/todos');

        $this->assertResponseIsSuccessful();

        $client->request(Request::METHOD_GET, '/api/todos?query=task');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testAdd(): void
    {
        $faker = Factory::create();

        $client = static::createClient();
        $client->request(Request::METHOD_POST, '/api/todos', [], [], [], json_encode([
            'title' => $faker->text(128),
            'description' => $faker->paragraph(3),
            'due' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s'),
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $newTodo = json_decode($client->getInternalResponse()->getContent(), true);
        $this->assertNotEmpty($newTodo['id']);
    }

    public function testAddWithParent(): void
    {
        $faker = Factory::create();

        $client = static::createClient();
        $client->request(Request::METHOD_POST, '/api/todos', [], [], [], json_encode([
            'title' => $faker->text(128),
            'description' => $faker->paragraph(3),
            'due' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s'),
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testUpdate(): void
    {
        $faker = Factory::create();

        $client = static::createClient();
        $client->request(Request::METHOD_PUT, '/api/todos/21', [], [], [], json_encode([
            'title' => $faker->text(128),
            'description' => $faker->paragraph(3),
            'due' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s'),
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testComplete(): void
    {
        $faker = Factory::create();

        $client = static::createClient();
        $client->request(Request::METHOD_PATCH, '/api/todos/21');

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testDelete(): void
    {
        $faker = Factory::create();

        $client = static::createClient();
        $client->request(Request::METHOD_DELETE, '/api/todos/21');

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
