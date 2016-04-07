<?php

namespace FrontBundle\Controller;

use MainBundle\Entity\Advert;
use MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class WhishlistController extends Controller
{

    public function userWhishlistAction(Request $request){
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->get('security.context')->getToken()->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $em = $this->getDoctrine()->getManager();
        $whislistAdverts = $em->getRepository('MainBundle:Whishlist')->findWhishlistAdvertByUserOnFrontend($user);


        return $this->render('FrontBundle:Whishlist:show-user-logged.html.twig', array(
            'whislists' => $whislistAdverts,
        ));

    }

    public function addAdvertToWhislistAction(Request $request, Advert $advert)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->get('security.context')->getToken()->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User.');
        }

        if (!$advert) {
            throw $this->createNotFoundException('Unable to find Advert.');
        }

        $whislistManager = $this->get('whishlist.manager');
        $whislistManager->persist($advert, $user);
        $session = $request->getSession();
        if(!$whislistManager){
            $session->getFlashBag()->add('success', $advert->getTitle().' ajouté');
        }else{
            $session->getFlashBag()->add('info', $advert->getTitle().' est déjà dans votre whislist!');
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}
