<?php

namespace App\Repository;

use App\Entity\Lectur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lectur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lectur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lectur[]    findAll()
 * @method Lectur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LecturRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lectur::class);
    }

    // /**
    //  * @return Lectur[] Returns an array of Lectur objects
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
    public function findOneBySomeField($value): ?Lectur
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
