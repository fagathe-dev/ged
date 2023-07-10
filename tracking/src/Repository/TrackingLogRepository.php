<?php

namespace Tracking\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tracking\Entity\TrackingLog;

/**
 * @extends ServiceEntityRepository<TrackingLog>
 *
 * @method TrackingLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrackingLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrackingLog[]    findAll()
 * @method TrackingLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackingLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrackingLog::class);
    }

    public function save(TrackingLog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TrackingLog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
//     * @return TrackingLog[] Returns an array of TrackingLog objects
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

    //    public function findOneBySomeField($value): ?TrackingLog
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}