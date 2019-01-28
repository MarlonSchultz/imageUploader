<?php

namespace App\Repository;

use App\Entity\UploadedImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UploadedImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method UploadedImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method UploadedImages[]    findAll()
 * @method UploadedImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UploadedImagesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UploadedImages::class);
    }

    // /**
    //  * @return UploadedImages[] Returns an array of UploadedImages objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UploadedImages
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
