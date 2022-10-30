<?php

namespace App\Tests\Functional;

use App\Entity\ToDo;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ToDosTest extends WebTestCase
{
    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

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
        $client = static::createClient();
        $client->request(Request::METHOD_POST, '/api/todos', [], [], [], json_encode([
            'title' => $this->faker->text(128),
            'description' => $this->faker->paragraph(3),
            'due' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s'),
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $newTodo = json_decode($client->getInternalResponse()->getContent(), true);
        $this->assertNotEmpty($newTodo['id']);
    }

    public function testAddWithParent(): void
    {
        $client = static::createClient();
        $parent = $this->getManager()->getRepository(ToDo::class)->findOneBy([]);

        $client->request(Request::METHOD_POST, '/api/todos', [], [], [], json_encode([
            'title' => $this->faker->text(128),
            'description' => $this->faker->paragraph(3),
            'due' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s'),
            'parent_id' => $parent->getId(),
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testUpdate(): void
    {
        $client = static::createClient();
        $toDo = $this->getManager()->getRepository(ToDo::class)->findOneBy([]);
        $path = sprintf('/api/todos/%d', $toDo->getId());

        $client->request(Request::METHOD_PUT, $path, [], [], [], json_encode([
            'title' => $this->faker->text(128),
            'description' => $this->faker->paragraph(3),
            'due' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s'),
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testComplete(): void
    {
        $client = static::createClient();
        $toDo = $this->getManager()->getRepository(ToDo::class)->findOneBy([]);
        $path = sprintf('/api/todos/%d', $toDo->getId());

        $client->request(Request::METHOD_PATCH, $path);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testDelete(): void
    {
        $client = static::createClient();
        $toDo = $this->getManager()->getRepository(ToDo::class)->findOneBy([]);
        $path = sprintf('/api/todos/%d', $toDo->getId());

        $client->request(Request::METHOD_DELETE, $path);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    private function getManager(): EntityManagerInterface
    {
        return self::getContainer()->get('doctrine')->getManager();
    }
}
