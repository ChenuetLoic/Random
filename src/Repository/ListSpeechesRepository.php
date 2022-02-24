<?php

namespace App\Repository;

use App\Entity\ListSpeeches;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ListSpeeches|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListSpeeches|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListSpeeches[]    findAll()
 * @method ListSpeeches[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListSpeechesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListSpeeches::class);
    }

    public function dropTable()
    {
        return $this->createQueryBuilder("l")
            ->delete()
            ->getQuery()
            ->execute();
    }

    // /**
    //  * @return ListSpeeches[] Returns an array of ListSpeeches objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ListSpeeches
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
