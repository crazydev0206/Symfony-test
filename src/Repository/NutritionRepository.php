<?php

namespace App\Repository;

use App\Entity\Nutrition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Nutrition>
 *
 * @method Nutrition|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nutrition|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nutrition[]    findAll()
 * @method Nutrition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NutritionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nutrition::class);
    }

    public function save(Nutrition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Nutrition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     *  @return Nutrition[] Returns an array of Nutrition objects
     * @return array
     */
    public function findNutritionFactsByFavorites(): array
    {
        $qb = $this->createQueryBuilder('n');
        $qb->select('SUM(n.carbohydrates) as totalCarbs, SUM(n.protein) as totalProtein, SUM(n.fat) as totalFat, SUM(n.calories) as totalCalories, SUM(n.sugar) as totalSugar')
        ->innerJoin('n.fruit', 'f')
            ->andWhere('f.isFavorite = true');

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result ? $result : [];
    }

//    /**
//     * @return Nutrition[] Returns an array of Nutrition objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Nutrition
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
