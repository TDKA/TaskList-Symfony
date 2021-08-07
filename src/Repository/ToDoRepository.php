<?php

namespace App\Repository;

use App\Entity\ToDo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ToDo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToDo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToDo[]    findAll()
 * @method ToDo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToDoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToDo::class);
    }

    /**
     * @return ToDo[] Returns an array of ToDo objects
     */

    public function findByUserSortedByMostRecent($user)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :val')
            ->setParameter('val', $user)
            ->orderBy('t.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    /**
     * @return ToDo[] Returns an array of ToDo objects
     */

    public function findByUserSortedByLessRecent($user)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :val')
            ->setParameter('val', $user)
            ->orderBy('t.createdAt', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ToDo[] Returns an array of ToDo objects
     */

    public function findByUserSortedByMostUrgent($user)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :val')
            ->setParameter('val', $user)
            ->orderBy('t.dueDate', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    /**
     * @return ToDo[] Returns an array of ToDo objects
     */

    public function findByUserSortedByLessUrgent($user)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :val')
            ->setParameter('val', $user)
            ->orderBy('t.dueDate', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }









    /*
    public function findOneBySomeField($value): ?ToDo
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
