<?php

namespace FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request, $page)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $allAdverts = $em->getRepository('MainBundle:Advert')
            ->getListPublicAdverts();

        if (!$allAdverts) {
            throw $this->createNotFoundException('Unable to find.');
        }

        if(empty($page)){
            $page = $request->query->getInt('page', 1);
        }

        $paginator = $this->get('knp_paginator');
        $adverts = $paginator->paginate(
            $allAdverts,
            $page,
            10
        );

        return $this->render('FrontBundle:Default:index.html.twig',array(
            'adverts' => $adverts
        ));
    }
}
