<?php

namespace FrontBundle\Controller;

use FrontBundle\Form\AdvertSearchType;
use FrontBundle\Form\SearchType;
use MainBundle\Entity\AdvertSearch;
use MainBundle\Entity\Search;
use Symfony\Component\Form\FormError;
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
        $form = $this->createForm(new AdvertSearchType());

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
            'search_form' => $form->createView()
        ));
    }

    /**
     * List adverts par category slug
     * @return Adverts
     */
    public function showAdvertsByCategorySlugAction(Request $request, Category $category, $page){

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new AdvertSearchType());

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
                'adverts' => $adverts,
                'search_form' => $form->createView()
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

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $session = $request->getSession();

        $advert = new Advert();
        $form = $this->createForm('FrontBundle\Form\AdvertType', $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $city_name = $form->get('location')->getData()? preg_replace('#[^a-z]#i','', $form->get('location')->getData()) : null;
            $zipcode = $form->get('zipcode')->getData()? $form->get('zipcode')->getData() : null;
            if($city_name!= null && $zipcode != null){
                $city = $em->getRepository('MainBundle:City')->findOneCityByNameOrZipcode($city_name, $zipcode);
                if(!$city){
                    $session->getFlashBag()->add('warning', 'Impossible de trouver votre ville');
                    return $this->render('FrontBundle:Advert:new-user-logged.html.twig', array(
                        'advert' => $advert,
                        'form' => $form->createView(),
                    ));
                }
            }
            $advert->setLocation($city->getCityNameReal());
            $advert->setZipcode($city->getCityZipcode());
            $advert->setLongitude($city->getCityLongitudeDeg());
            $advert->setLatitude($city->getCityLatitudeDeg());
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
                ->setBody($this->renderView('FrontBundle:Mail:validation.html.twig',array('user' => $user, 'token' => $advert->getToken() ,'advert' => $advert)));

            $this->get('mailer')->send($message);

            return $this->redirectToRoute('front_logged_advert_show', array('id' => $advert->getId()));

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
        $session = $request->getSession();
        //$deleteForm = $this->createDeleteForm($advert);
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
                $city_name = $editForm->get('location')->getData()? preg_replace('#[^a-z]#i','', $editForm->get('location')->getData()) : null;
                $zipcode = $editForm->get('zipcode')->getData()? $editForm->get('zipcode')->getData() : null;
                if($city_name!= null && $zipcode != null){
                    $city = $em->getRepository('MainBundle:City')->findOneCityByNameOrZipcode($city_name, $zipcode);
                    if(!$city){
                        $error = 'Impossible de trouver votre ville';
                        $session->getFlashBag()->add('warning', $error);
                        $formerr = new FormError($error);
                        $editForm->get('location')->addError($formerr);
                        return $this->render('FrontBundle:Advert:new-user-logged.html.twig', array(
                            'advert' => $advert,
                            'form' => $editForm->createView(),
                        ));
                    }
                }
                $advert->setLocation($city->getCityNameReal());
                $advert->setZipcode($city->getCityZipcode());
                $advert->setLongitude($city->getCityLongitudeDeg());
                $advert->setLatitude($city->getCityLatitudeDeg());
                $em->persist($advert);
                $em->flush();

                return $this->redirectToRoute('front_logged_advert_edit', array('id' => $advert->getId()));
            }
        }else{

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

    public function searchFormGetAction()
    {
        $form = $this->createForm(new AdvertSearchType());
        return $this->render('FrontBundle:Search:search-form-get-frontend.html.twig', array(
            'search_form' => $form->createView()
        ));
    }

    public function searchGetAction(Request $request)
    {
        if ($this->get('request')->getMethod() == 'GET')
        {

            $em = $this->getDoctrine()->getManager();
            $keyword = $request->query->get('keyword') ? $request->query->get('keyword') : null;
            $category = $request->query->get('category') ? $request->query->get('category') : null;
            $city = $request->query->get('city') ? $request->query->get('city') : null;

            if(empty($page)){
                $page = $request->query->getInt('page', 1);
            }

            $advertSearch = new AdvertSearch();
            $advertSearch->setKeyword($keyword);
            $advertSearch->setCategory($category);
            $advertSearch->setCity($city);

            //$form = $this->createForm(new AdvertSearchType($em, $keyword, $category, $city),$advertSearch);
            $form = $this->createForm(new AdvertSearchType($em, $keyword, $category, $city),$advertSearch);
            $form->handleRequest($request);

            //$allAdverts = $em->getRepository('MainBundle:Advert')->search($keyword, $category, $city);
            $adverts = $em->getRepository('MainBundle:Advert')->search($keyword, $category, $city);

            /*$paginator = $this->get('knp_paginator');
            $adverts = $paginator->paginate(
                $allAdverts,
                $page,
                2
            );
            $adverts->setParam('keyword', $keyword);
            $adverts->setParam('category', $category);
            $adverts->setParam('city', $city);*/

        } else {
            throw $this->createNotFoundException('La page n\'existe pas.');
        }

        return $this->render('FrontBundle:Search:search-result.html.twig',
            array(
                'adverts' => $adverts,
                'page' => $page,
                'keyword' => $keyword,
                'category' => $category,
                'city' => $city,
                'search_form' => $form->createView()
            )
        );
    }



}
