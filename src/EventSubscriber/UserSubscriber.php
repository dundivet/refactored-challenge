<?php

namespace App\EventSubscriber;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Context\ExceptionContext;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Exception\BaseException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserSubscriber implements EventSubscriberInterface
{
    public const DEFAULT_ADMIN_USERNAME = 'admin@example';

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'onBeforeUserEvent',
            BeforeEntityUpdatedEvent::class => 'onBeforeUserEvent',
            BeforeEntityDeletedEvent::class => 'onBeforeUserDeletedEvent',
        ];
    }

    public function onBeforeUserEvent($event)
    {
        /** @var User $user  */
        $user = $event->getEntityInstance();

        if (!($user instanceof User)) {
            return;
        }

        if (!empty($user->getPlainPassword())) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPlainPassword());
            $user->setPassword($hashedPassword);
        }
    }

    public function onBeforeUserDeletedEvent($event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

        if (self::DEFAULT_ADMIN_USERNAME === $entity->getUserIdentifier()) {
            throw new BaseException(new ExceptionContext('Error deleting User', 'You can\'t delete System Admin.', [], Response::HTTP_FORBIDDEN));
        }
    }
}
