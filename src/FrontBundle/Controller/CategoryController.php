<?php

namespace FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    public function indexAction(Request $request, $slug = null)
    {
        $em = $this->getDoctrine()->getManager();

        $allcategories = $em->getRepository('MainBundle:Category')
            ->getListCategoryBySlugWithAdvertsOnFrontend($slug);


        if (!$allcategories) {
            throw $this->createNotFoundException('Unable to find.');
        }

        $paginator = $this->get('knp_paginator');
        $categories = $paginator->paginate(
            $allcategories,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('FrontBundle:Category:index.html.twig',array(
            'category' => $categories
        ));
    }

    public function renderListCategoryMenuAction(){
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('MainBundle:Category')->findAll();

        return $this->render('FrontBundle:Category:_list-category-menu.html.twig',array(
            'categories' => $categories
        ));
    }
}
