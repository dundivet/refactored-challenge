<?php

namespace App\Tests\Functional;

use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class ToDosTest extends WebTestCase
{
    public function testAll(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/api/todos');

        $this->assertResponseIsSuccessful();

        $client->request(Request::METHOD_GET, '/api/todos?query=task');
        $this->assertResponseIsSuccessful();
    }

    public function testAdd(): void
    {
        $faker = Factory::create();

        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_POST, '/api/todos', [], [], [], json_encode([
            'title' => $faker->text(128),
            'description' => $faker->paragraph(3),
            'dueDate' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s'),
        ]));

        $this->assertResponseIsSuccessful();
    }

    public function testAddWithParent(): void
    {
        $faker = Factory::create();

        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_POST, '/api/todo/10', [], [], [], json_encode([
            'title' => $faker->text(128),
            'description' => $faker->paragraph(3),
            'dueDate' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s'),
        ]));

        $this->assertResponseIsSuccessful();
    }

    public function testUpdate(): void
    {
        $faker = Factory::create();

        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_PUT, '/api/todo/10', [], [], [], json_encode([
            'title' => $faker->text(128),
            'description' => $faker->paragraph(3),
            'dueDate' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s'),
        ]));

        $this->assertResponseIsSuccessful();
    }

    public function testComplete(): void
    {
        $faker = Factory::create();

        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_PATCH, '/api/todo/10');

        $this->assertResponseIsSuccessful();
    }

    public function testDelete(): void
    {
        $faker = Factory::create();

        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_DELETE, '/api/todo/10');

        $this->assertResponseIsSuccessful();
    }
}
