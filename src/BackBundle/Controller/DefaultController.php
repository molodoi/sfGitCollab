<?php

namespace BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $allAdvertsNotActive = $em->getRepository('MainBundle:Advert')->findBy(array('isActivated' => false));
        $allUsersNotActive = $em->getRepository('MainBundle:User')->findBy(array('enabled' => true));

        if(empty($page)){
            $page = $request->query->getInt('page', 1);
        }

        $paginator = $this->get('knp_paginator');
        $advertsnotactive = $paginator->paginate(
            $allAdvertsNotActive,
            $page,
            4
        );

        $userssnotactive = $paginator->paginate(
            $allUsersNotActive,
            $page,
            4
        );


        return $this->render('BackBundle:Default:index.html.twig',
            array(
                'advertsnotactive' => $advertsnotactive,
                'userssnotactive' => $userssnotactive,
            )
        );
    }
}
