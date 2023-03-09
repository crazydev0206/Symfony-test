<?php

namespace App\Repository;

use App\Entity\Fruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fruit>
 *
 * @method Fruit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fruit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fruit[]    findAll()
 * @method Fruit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fruit::class);
    }

    public function save(Fruit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fruit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Fruit[] Returns an array of Fruit objects
    */
   public function findAllPaginatedAndFiltered($name, $family): array
   {
       return $this->createQueryBuilder('f')
           ->andWhere('f.name LIKE :name')
           ->setParameter('name', '%' . $name . '%')
           ->andWhere('f.family LIKE :family')
           ->setParameter('family', '%' . $family . '%')
           ->orderBy('f.id', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

    /**
     * @return Fruit[] Returns an array of Fruit objects
     */
    public function findAllFruitsFavoris($value): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.isFavorite = :favoris')
            ->setParameter('favoris', $value)
            ->getQuery()
            ->getResult();
    }

    public function countFavorites(): int
    {
        $qb = $this->createQueryBuilder('f');
        $qb->select('COUNT(f.id)')
            ->andWhere('f.isFavorite = :isFavorite')
            ->setParameter('isFavorite', true);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

}
