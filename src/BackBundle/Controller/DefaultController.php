<?php

namespace BackBundle\Controller;

use MainBundle\Entity\Advert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{
    public function indexAction(Request $request, $page_advertsnotactive = null, $page_usersnotactive = null)
    {
        $this->grantAccessUserSecurity();

        $em = $this->getDoctrine()->getManager();
        $allAdvertsNotActive = $em->getRepository('MainBundle:Advert')->findBy(array('isActivated' => false));
        $allUsersNotActive = $em->getRepository('MainBundle:User')->findBy(array('enabled' => true));

        if(empty($page_advertsnotactive)){
            $page_advertsnotactive = $request->query->getInt('page_advertsnotactive', 1);
        }
        if(empty($page_usersnotactive)){
            $page_usersnotactive = $request->query->getInt('page_usersnotactive', 1);
        }

        $paginator = $this->get('knp_paginator');
        $advertsnotactive = $paginator->paginate(
            $allAdvertsNotActive,
            $page_advertsnotactive,
            4,
            array('pageParameterName' => 'page_advertsnotactive')
        );
        $advertsnotactive->setParam('page_advertsnotactive', $page_advertsnotactive);

        $usersnotactive = $paginator->paginate(
            $allUsersNotActive,
            $page_usersnotactive,
            2,
            array('pageParameterName' => 'page_usersnotactive')
        );
        $usersnotactive->setParam('page_usersnotactive', $page_usersnotactive);

        return $this->render('BackBundle:Default:index.html.twig',
            array(
                'advertsnotactive' => $advertsnotactive,
                'usersnotactive' => $usersnotactive,
                'page_advertsnotactive' => $page_advertsnotactive,
                'page_usersnotactive' => $page_usersnotactive
            )
        );
    }

    public function activateAnnonceByIdAction(Request $request, Advert $advert)
    {
        $this->grantAccessUserSecurity();
        if (!$advert) {
            throw $this->createNotFoundException('Unable to find Advert entity.');
        }

        $em = $this->getDoctrine()->getManager();
        $advert->setIsActivated(true);
        $em->flush($advert);

        if($request->isXmlHttpRequest()){
            return new JsonResponse(array('success' => true));
        }

        return $this->redirectToRoute('back_homepage');

    }
}
