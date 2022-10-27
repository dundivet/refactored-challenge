<?php

namespace App\Manager;

use App\Entity\Tag;
use App\Entity\ToDo;
use App\Repository\ToDoRepository;
use Doctrine\Common\Collections\ArrayCollection;
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

    public function update(ToDo $toDoObj, ? ToDo $toDo = null): ? ToDo
    {
        try {
            if (null !== $toDo) {
                if ($toDo->getTitle() !== $toDoObj->getTitle()) {
                    $toDo->setTitle($toDoObj->getTitle());
                }
                if ($toDo->getDescription() !== $toDoObj->getDescription()) {
                    $toDo->setDescription($toDoObj->getDescription());
                }
                if ($toDo->getDue() !== $toDoObj->getDue()) {
                    $toDo->setDue($toDoObj->getDue());
                }

                if (null !== $toDoObj->getParent()) {
                    $toDo->setParent($toDoObj->getParent());
                }

                $oldTags = new ArrayCollection($toDo->getTags()->toArray());
                foreach ($toDoObj->getTags() as $tag) {
                    if (!$toDo->getTags()->contains($tag)) {
                        $toDo->addTag($tag);
                    } else {
                        $oldTags->removeElement($tag);
                    }
                }

                foreach ($oldTags as $tag) {
                    $toDo->removeTag($tag);
                }

                $this->repository->save($toDo, true);

                return $toDo;
            }

            $this->repository->save($toDoObj, true);
            
            return $toDoObj;
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
