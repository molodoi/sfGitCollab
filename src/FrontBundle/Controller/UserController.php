<?php

namespace FrontBundle\Controller;

use MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function showAction(Request $request, User $user)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User.');
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MainBundle:User')->findOneById($user->getId());

        return $this->render('FrontBundle:User:show.html.twig', array(
            'user' => $user,
        ));

    }


    public function deleteAvatarAction(Request $request,User $user){
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User.');
        }
        $userManager = $this->get('fos_user.user_manager');
        $path = $user->getPath();

        $thumbnails = array('50_','150_','300_','320150_','800500_');
        $directoryPath = $this->container->getParameter('kernel.root_dir') . '/../web/uploads/avatars/';
        $directoryPath = preg_replace("/app..../i", "", $directoryPath);
        foreach($thumbnails as $prefix)
        {
            if (file_exists($directoryPath.$prefix.$path))
            {
                unlink($directoryPath.$prefix.$path);
            }
        }
        if(file_exists($directoryPath.$path)){
            unlink($directoryPath.$path);
        }
        $user->setPath(null);
        $userManager->updateUser($user);

        if($request->isXmlHttpRequest()){
            return new JsonResponse(array('success' => true));
        }

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);

        /*return $this->redirect($this->generateUrl('back_user_edit', array('id' => $user->getId())));*/
    }
}