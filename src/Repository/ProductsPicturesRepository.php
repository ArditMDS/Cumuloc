<?php

namespace App\Repository;

use App\Entity\ProductsPictures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductsPictures>
 *
 * @method ProductsPictures|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductsPictures|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductsPictures[]    findAll()
 * @method ProductsPictures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsPicturesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductsPictures::class);
    }

//    /**
//     * @return ProductsPictures[] Returns an array of ProductsPictures objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProductsPictures
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
