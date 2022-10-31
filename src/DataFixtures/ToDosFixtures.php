<?php

namespace App\DataFixtures;

use App\Entity\ToDo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ToDosFixtures extends Fixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    const DEFAULT_TODOS_COUNT = 20;

    private ContainerInterface $container;

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $kernel = $this->container->get('kernel');

        foreach (range(1, self::DEFAULT_TODOS_COUNT) as $i) {
            $todo = $this->instanceToDo($kernel->getEnvironment());

            $randSubtasks = $this->faker->numberBetween(0, 5);
            for($i = 0; $i < $randSubtasks; $i++) {
                $subtask = $this->instanceToDo($kernel->getEnvironment());
                $manager->persist($subtask);

                $todo->addSubtask($subtask);
            }

            $manager->persist($todo);
        }

        $manager->flush();
    }

    private function instanceToDo(string $env): ToDo
    {
        $todo = new ToDo();
        $todo->setTitle($this->faker->text(128));
        $todo->setDescription($this->faker->paragraph(3));
        $todo->setDue($this->faker->dateTimeBetween('+1 days', '+3 months'));

        $tagNames = $this->faker->randomElements(TagsFixtures::DEFAULT_TAGS, $this->faker->numberBetween(1, 3));
        foreach ($tagNames as $tagName) {
            $todo->addTag($this->getReference($tagName));
        }

        if ('test' === $env) {
            $todo->setOwner($this->getReference('user_test'));
        } else {
            $todo->setOwner($this->getReference('user_admin'));
        }

        return $todo;
    }

	function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
	}

    public function getOrder(): int
    {
        return 1000;
    }
}
