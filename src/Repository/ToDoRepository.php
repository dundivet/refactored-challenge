<?php

namespace App\Repository;

use App\Entity\ToDo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @extends ServiceEntityRepository<ToDo>
 *
 * @method ToDo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToDo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToDo[]    findAll()
 * @method ToDo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToDoRepository extends ServiceEntityRepository
{
    private Security $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, ToDo::class);

        $this->security = $security;
    }

    public function save(ToDo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ToDo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByQuery(?string $query, bool $onlyRoot=true): array
    {
        $queryBuilder = $this->createQueryBuilder('todo');
        $queryBuilder
            ->innerJoin('todo.owner', 'owner')
            ->where($queryBuilder->expr()->eq('owner.email', ':owner'))
            ->setParameter('owner', $this->security->getUser()->getUserIdentifier());

        if (null !== $query && '' !== $query) {
            $queryBuilder
            ->leftJoin('todo.tags', 'tag')
            ->andWhere($queryBuilder->expr()->orX(
                $queryBuilder->expr()->like('todo.title', ':query'),
                $queryBuilder->expr()->like('todo.description', ':query'),
                $queryBuilder->expr()->like('tag.name', ':query')
            ))
            ->setParameter('query', sprintf('%%%s%%', $query));
        }

        if ($onlyRoot) {
            $queryBuilder->andWhere($queryBuilder->expr()->isNull('todo.parent'));
        }

        $queryBuilder->orderBy('todo.due', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

//    /**
//     * @return ToDo[] Returns an array of ToDo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ToDo
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
