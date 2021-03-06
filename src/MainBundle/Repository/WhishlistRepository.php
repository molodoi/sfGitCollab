<?php

namespace MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * WhishlistRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WhishlistRepository extends EntityRepository
{

    public function findWhishlistAdvertByUserOnFrontend($user)
    {
        $qb = $this->createQueryBuilder('w')
            ->addSelect('advert')
            ->addSelect('files')
            ->addSelect('category')
            ->leftJoin('w.advert', 'advert')
            ->leftJoin('advert.fileadverts', 'files')
            ->leftJoin('advert.category', 'category')
            ->where('w.user = :user')
            ->orderBy('w.createdAt', 'DESC')
            ->setParameter('user', $user)
        ;

        return $qb->getQuery()->getResult();
    }


    public function checkWhislistExist($user, $advert){
        $qb = $this->createQueryBuilder('w')
            ->addSelect('user')
            ->addSelect('advert')
            ->leftJoin('w.user', 'user')
            ->leftJoin('w.advert', 'advert')
            ->where('w.user = :user')
            ->andWhere('w.advert = :advert')
            ->setParameter('user', $user)
            ->setParameter('advert', $advert)
        ;
        return $qb->getQuery()->getResult();
    }

    public function countFullWhishlists()
    {
        $q = $this->createQueryBuilder('w')
            ->select('COUNT(w) as nbWhishlist')
            ->getQuery()
        ;

        return $q->getSingleScalarResult();
    }
    public function getListWhishlists($page = 1, $maxperpage = 12)
    {
        $q = $this->createQueryBuilder('w')
            ->orderBy('w.createdAt', 'DESC')
        ;

        $q->setFirstResult(($page-1) * $maxperpage)
            ->setMaxResults($maxperpage);

        return new Paginator($q);
    }
}
