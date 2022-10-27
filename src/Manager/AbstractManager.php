<?php 

namespace App\Manager;

use Doctrine\ORM\EntityRepository;
use Psr\Log\LoggerInterface;

abstract class AbstractManager
{
    protected EntityRepository $repository;

    protected LoggerInterface $logger;

    public function __construct(EntityRepository $repository, LoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->logger = $logger;
    }

    abstract public function findById(int $id): mixed;
}
