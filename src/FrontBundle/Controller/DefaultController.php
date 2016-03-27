<?php

namespace FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request, $page)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('search_form');

        $allAdverts = $em->getRepository('MainBundle:Advert')
            ->getListPublicAdverts();

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
            'adverts' => $adverts,
            'search_form' => $form->createView()
        ));
    }
}
