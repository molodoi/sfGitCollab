<?php

namespace FrontBundle\Services;

use Doctrine\ORM\EntityManager;
use MainBundle\Entity\Whishlist;

class WhishlistManager
{
    private $em;
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository('MainBundle:Whishlist');
    }

    public function checkWhislistExist($user, $advert){
        $whish = $this->repository->checkWhislistExist($user, $advert);
        if($whish){
            return $whish;
        }else{
            return false;
        }
    }

    public function persist($advert, $user){
        $check = $this->checkWhislistExist($user, $advert);
        if(!$check){
            $whishlist = new Whishlist();
            $whishlist->setAdvert($advert);
            $whishlist->setUser($user);
            $this->em->persist($whishlist);
            $this->em->flush();
            return $whishlist;
        }else{
            return false;
        }

    }
}