<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function findByClients($searchClients)
    {
        $query = $this->getEntityManager()
            ->createQuery("
            SELECT cl FROM App\Entity\Client cl
            WHERE cl.name LIKE :name ")
            ->setParameter('name', '%' . $searchClients . '%');

        return $queryEvent = $query->getResult();
    }

    public function findByAddress($searchAddress)
    {
        $query = $this->getEntityManager()
            ->createQuery("
            SELECT cl FROM App\Entity\Client cl
            WHERE cl.address LIKE :address ")
            ->setParameter('address', '%' . $searchAddress . '%');

        return $queryEvent = $query->getResult();
    }



    // /**
    //  * @return Client[] Returns an array of Client objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Client
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
