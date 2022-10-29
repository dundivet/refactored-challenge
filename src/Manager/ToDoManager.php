<?php

namespace App\Manager;

use App\Entity\ToDo;
use App\Repository\ToDoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Log\LoggerInterface;

class ToDoManager extends AbstractManager
{
    public function __construct(ToDoRepository $repository, LoggerInterface $logger)
    {
        parent::__construct($repository, $logger);
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
            $this->logger->error(sprintf('An error occurred while updating ToDo entity. Details: %s', $e->getMessage()));
        }

        return null;
    }

    public function remove(ToDo $toDoObj): bool
    {
        try {
            $this->repository->remove($toDoObj, true);

            return true;
        } catch (\Exception $e) {
            $this->logger->error(sprintf('An error occurred while deleting ToDo entity. Details: %s', $e->getMessage()));
        }

        return false;
    }

    public function complete(ToDo $toDoObj): bool
    {
        
        try {
            $toDoObj->setCompleted(true);
            $this->repository->save($toDoObj, true);

            return true;
        } catch (\Exception $e) {
            $this->logger->error(sprintf('An error occurred while completing ToDo entity. Details: %s', $e->getMessage()));
        }

        return false;
    }

    public function findById(int $id): ?ToDo
    {
        return $this->repository->find($id);
    }
}
