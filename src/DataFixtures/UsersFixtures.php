<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UsersFixtures extends Fixture implements ContainerAwareInterface
{
    private ContainerInterface $container;

    public function load(ObjectManager $manager): void
    {
        $adminUser = new User();
        $adminUser
            ->setEmail('admin@example')
            ->setPassword('$2y$13$LZaZbXRfxn9tkXRxv2M5Qu1yCdiDWz2NS3SHahY77Nw93jgaae0J6')
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($adminUser);

        $kernel = $this->container->get('kernel');
        if ('test' === $kernel->getEnvironment()) {
            $testUser = new User();
            $testUser
                ->setEmail('test@example')
                ->setPassword('$2y$04$xPOCDBr/szbjDXK.TkftRe0.b38CbK5yKqi4py8UXBxoT6oMuJBTy')
                ->setRoles(['ROLE_USER']);

            $manager->persist($testUser);
        }

        $manager->flush();
    }

	/**
	 * Sets the container.
	 *
	 * @param \Symfony\Component\DependencyInjection\ContainerInterface|null $container
	 *
	 * @return mixed
	 */
	function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
	}
}
