<?php

namespace App\DataFixtures;

use App\Entity\ToDo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ToDosFixtures extends Fixture implements OrderedFixtureInterface
{
    const DEFAULT_TODOS_COUNT = 20;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        foreach (range(1, self::DEFAULT_TODOS_COUNT) as $i) {
            $todo = new ToDo();
            $todo->setTitle($faker->text(128));
            $todo->setDescription($faker->paragraph(3));
            $todo->setDue($faker->dateTimeBetween('+1 days', '+3 months'));

            $tagNames = $faker->randomElements(TagsFixtures::DEFAULT_TAGS, $faker->numberBetween(1, 3));
            foreach ($tagNames as $tagName) {
                $todo->addTag($this->getReference($tagName));
            }

            $manager->persist($todo);
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
