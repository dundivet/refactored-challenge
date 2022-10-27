<?php

namespace App\Manager;

use App\Entity\Tag;
use App\Entity\ToDo;
use App\Repository\ToDoRepository;
use Psr\Log\LoggerInterface;

class ToDoManager
{
    private ToDoRepository $repository;

    private LoggerInterface $logger;

    public function __construct(ToDoRepository $repository, LoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->logger = $logger;
    }

    public function search(?string $query): array
    {
        return $this->repository->findByQuery($query);
    }

    public function create(ToDo $newToDo): ?ToDo
    {
        try {
            $this->repository->save($newToDo, true);

            return $newToDo;
        } catch (\Exception $e) {
            $this->logger->error(sprintf('An error occurred while creating a new ToDo. Details: %s', $e->getMessage()));
        }

        return null;
    }

    public function getRepository(): ToDoRepository
    {
        return $this->repository;
    }
}
