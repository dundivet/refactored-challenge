<?php

namespace App\Manager;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Psr\Log\LoggerInterface;

class TagManager extends AbstractManager
{
    public function __construct(TagRepository $repository, LoggerInterface $logger)
    {
        parent::__construct($repository, $logger);
    }

    public function search(?string $query): array
    {
        return $this->repository->findByQuery($query);
    }

    public function update(Tag $tagObj, ?Tag $tag = null): ?Tag
    {
        try {
            if (null !== $tag) {
                if ($tag->getName() !== $tagObj->getName()) {
                    $tag->setName($tagObj->getName());
                }

                $this->repository->save($tag, true);

                return $tag;
            }

            $this->repository->save($tagObj, true);
            
            return $tagObj;
        } catch (\Exception $e) {
            $this->logger->error(sprintf('An error occurred while updating Tag entity. Details: %s', $e->getMessage()));
        }

        return null;
    }

    public function findById(int $id): ?Tag
    {
        return $this->repository->find($id);
    }
}