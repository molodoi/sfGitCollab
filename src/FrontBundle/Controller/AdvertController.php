<?php

namespace FrontBundle\Controller;

use FrontBundle\Form\AdvertSearchType;
use MainBundle\Entity\AdvertSearch;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MainBundle\Entity\Advert;
use MainBundle\Entity\AdvertFile;
use FrontBundle\Form\AdvertType;
use MainBundle\Entity\Category;

/**
 * Advert controller.
 *
 */
class AdvertController extends Controller
{


    /**
     * List adverts by current user logged
     * @return Adverts
     */
    public function indexAdvertUserLoggedAction(Request $request, $page)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $allAdverts = $em->getRepository('MainBundle:Advert')->findAdvertsByUserOnFrontend($user);

        if(empty($page)){
            $page = $request->query->getInt('page', 1);
        }

        $paginator = $this->get('knp_paginator');
        $adverts = $paginator->paginate(
            $allAdverts,
            $page,
            10
        );

        return $this->render('FrontBundle:Advert:index-user-logged.html.twig', array(
            'adverts' => $adverts,
        ));
    }

    /**
     * List adverts par category slug
     * @return Adverts
     */
    public function showAdvertsByCategorySlugAction(Category $category, Request $request, $page){

        $em = $this->getDoctrine()->getManager();

        $allAdverts = $em->getRepository('MainBundle:Advert')->findAdvertsByCategoryOnFrontend($category);

        if(empty($page)){
            $page = $request->query->getInt('page', 1);
        }

        $paginator = $this->get('knp_paginator');
        $adverts = $paginator->paginate(
            $allAdverts,
            $page,
            5
        );

        return $this->render('FrontBundle:Default:index.html.twig',
            array(
                'adverts' => $adverts
            )
        );

    }

    /**
     * Show one advert by current user logged
     * @return Adverts
     */
    public function showAdvertUserLoggedAction(Request $request, Advert $advert)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $user = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('MainBundle:Advert')->findOneAdvertByUserByIdOnFrontend($advert->getId(), $user);

        return $this->render('FrontBundle:Advert:show-user-logged.html.twig', array(
            'advert' => $advert,
        ));
    }

    /**
     * Creates a new Advert entity.
     *
     */
    public function newAdvertUserLoggedAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->get('security.context')->getToken()->getUser();

        $advert = new Advert();
        $form = $this->createForm('FrontBundle\Form\AdvertType', $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $advert->setUser($user);
            $em->persist($advert);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('success', 'Votre Annonce a bien été ajouté et en attente de validation par notre équipe sous 24h.');

            //Ici le mail de validation
            $message = \Swift_Message::newInstance()
                ->setSubject('Validation d\'annonce ')
                ->setFrom(array('contact@ticme.fr' => "AtHome"))
                ->setTo(array('contact@ticme.fr' => "Ticme"))
                ->setCharset('utf-8')
                ->setContentType('text/html')
                ->setBody($this->renderView('FrontBundle:Mail:validation.html.twig',array('user' => $user)));

            $this->get('mailer')->send($message);

            return $this->redirectToRoute('front_logged_advert_show', array('id' => $advert->getId()));
            //return $this->redirectToRoute('front_logged_advert_index');
        }

        return $this->render('FrontBundle:Advert:new-user-logged.html.twig', array(
            'advert' => $advert,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Advert entity.
     *
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('MainBundle:Advert')->findAdvertBySlugOnFrontend($slug);

        if (!$advert) {
            throw $this->createNotFoundException('Unable to find Advert entity.');
        }
        return $this->render('FrontBundle:Advert:show.html.twig', array(
            'advert' => $advert,
        ));
    }

    /**
     * Displays a form to edit an existing Advert entity.
     *
     */
    public function editAdvertUserLoggedAction(Request $request, Advert $advert)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $deleteForm = $this->createDeleteForm($advert);
        $editForm = $this->createForm('FrontBundle\Form\AdvertType', $advert);
        $editForm->handleRequest($request);

        //Count Files
        //Current files and tags stored with Advert
        $currentFileAdvert = count($advert->getFileadverts());
        //Files stored in request / Post
        $advertsv = $request->files->get('advert') ? $request->files->get('advert'): null ;
        //Total Files Count / Sum
        $totalFiles = ($currentFileAdvert + count($advertsv['files']));
        if($totalFiles <= 5 && count($advertsv['files']) <= 5 ){
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($advert);
                $em->flush();

                return $this->redirectToRoute('front_logged_advert_edit', array('id' => $advert->getId()));
            }
        }else{
            $session = $request->getSession();
            $session->getFlashBag()->add('warning', 'Vous avez atteind la limite d\'upload de 5 fichiers');
        }

        return $this->render('FrontBundle:Advert:edit-user-logged.html.twig', array(
            'advert' => $advert,
            'edit_form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Advert entity.
     *
     */
    public function deleteAction(Request $request, Advert $advert)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createDeleteForm($advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($advert);
            $em->flush();
        }

        return $this->redirectToRoute('front_logged_advert_index');
    }

    /**
     * Creates a form to delete a Advert entity.
     *
     * @param Advert $advert The Advert entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Advert $advert)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('front_logged_advert_delete', array('id' => $advert->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function searchAction()
    {
        $form = $this->createForm(new AdvertSearchType());

        return $this->render('FrontBundle:Slots:search-form-frontend.html.twig', array('search_form' => $form->createView()));
    }


    public function searchPostAction(Request $request)
    {
        $advertSearch = new AdvertSearch();
        $form = $this->createForm(new AdvertSearchType(),$advertSearch);
        $form->handleRequest($this->get('request'));
        if ($this->get('request')->getMethod() == 'POST')
        {

            $keyword = $form->get('keyword')->getData() ? $form->get('keyword')->getData() : null;
            $category = $form->get('category')->getData() ? $form->get('category')->getData()->getId() : null;
            $city = $form->get('city')->getData() ? $form->get('city')->getData() : null;
            $em = $this->getDoctrine()->getManager();

            $allAdverts = $em->getRepository('MainBundle:Advert')->search($keyword, $category, $city);

            if(empty($page)){
                $page = $request->query->getInt('page', 1);
            }

            $paginator = $this->get('knp_paginator');
            $adverts = $paginator->paginate(
                $allAdverts,
                $page,
                5
            );

        } else {
            throw $this->createNotFoundException('La page n\'existe pas.');
        }

        return $this->render('FrontBundle:Default:index.html.twig', array('adverts' => $adverts, 'search_form' => $form));
    }

}
