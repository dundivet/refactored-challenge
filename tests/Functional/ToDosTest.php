<?php

namespace App\Tests\Functional;

use App\Entity\ToDo;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Core\User\UserInterface;

class ToDosTest extends WebTestCase
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
        $this->client->request(Request::METHOD_GET, '/api/todos');

        $this->assertResponseIsSuccessful();

        $this->client->request(Request::METHOD_GET, '/api/todos?query=task');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testAdd(): void
    {
        // $client = static::createClient();
        $this->client->request(Request::METHOD_POST, '/api/todos', [], [], [], json_encode([
            'title' => $this->faker->text(128),
            'description' => $this->faker->paragraph(3),
            'due' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s'),
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $newTodo = json_decode($this->client->getInternalResponse()->getContent(), true);
        $this->assertNotEmpty($newTodo['id']);
    }

    public function testAddWithParent(): void
    {
        // $client = static::createClient();
        $parent = $this->getManager()->getRepository(ToDo::class)->findOneBy([]);

        $this->client->request(Request::METHOD_POST, '/api/todos', [], [], [], json_encode([
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
        // $client = static::createClient();
        $toDo = $this->getManager()->getRepository(ToDo::class)->findOneBy([]);
        $path = sprintf('/api/todos/%d', $toDo->getId());

        $this->client->request(Request::METHOD_PUT, $path, [], [], [], json_encode([
            'title' => $this->faker->text(128),
            'description' => $this->faker->paragraph(3),
            'due' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s'),
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testComplete(): void
    {
        // $client = static::createClient();
        $toDo = $this->getManager()->getRepository(ToDo::class)->findOneBy([]);
        $path = sprintf('/api/todos/%d', $toDo->getId());

        $this->client->request(Request::METHOD_PATCH, $path);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testDelete(): void
    {
        // $client = static::createClient();
        $toDo = $this->getManager()->getRepository(ToDo::class)->findOneBy([]);
        $path = sprintf('/api/todos/%d', $toDo->getId());

        $this->client->request(Request::METHOD_DELETE, $path);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    private function getManager(): EntityManagerInterface
    {
        return self::getContainer()->get('doctrine')->getManager();
    }
}
