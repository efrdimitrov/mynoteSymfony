<?php


namespace App\Repository;


class SearchRepository
{
    public function findEntitiesByString($str){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT cl
                FROM AppBundle:Client cl
                WHERE cl.foo LIKE :str'
            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();
    }
}