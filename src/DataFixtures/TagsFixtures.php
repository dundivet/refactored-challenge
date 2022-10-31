<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TagsFixtures extends Fixture implements OrderedFixtureInterface
{
    const DEFAULT_TAGS = ['task', 'devops', 'study', 'work', 'home', 'personal'];

    public function load(ObjectManager $manager): void
    {
        foreach (self::DEFAULT_TAGS as $name) {
            $tag = new Tag();
            $tag->setName($name);

            $manager->persist($tag);
            $this->addReference($name, $tag);
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 200;
    }
}
